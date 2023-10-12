// Copyright 2013 Clark DuVall
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

if (typeof Object.create !== 'function') {
  Object.create = function (o) {
    function F() {}
    F.prototype = o
    return new F()
  }
}

if (!Function.prototype.bind) {
  Function.prototype.bind = function (oThis) {
    if (typeof this !== 'function') {
      // closest thing possible to the ECMAScript 5 internal IsCallable function
      throw new TypeError('Function.prototype.bind - what is trying to be bound is not callable')
    }

    var aArgs = Array.prototype.slice.call(arguments, 1),
      fToBind = this,
      fNOP = function () {},
      fBound = function () {
        return fToBind.apply(
          this instanceof fNOP && oThis ? this : oThis,
          aArgs.concat(Array.prototype.slice.call(arguments))
        )
      }

    fNOP.prototype = this.prototype
    fBound.prototype = new fNOP()

    return fBound
  }
}

var Terminal = {
  init: function (config, fs, commands, cb) {
    this._queue = []
    this._history = []
    this._historyIndex = -1
    this.loadConfig(config)

    if (commands) this.loadCommands(commands)

    if (fs) this.loadFS(fs, cb)
    else if (cb) cb()
  },

  loadFS: function (filesystem, cb) {
    this.fs = filesystem
    this._addDirs(this.fs, this.fs)
    cb && cb()
  },

  loadCommands: function (commands) {
    if (this.commands) {
      this.commands = { ...this.commands, ...commands }
    } else {
      this.commands = commands
      this.commands._terminal = this
    }
  },

  removeCommand: function (command) {
    delete this.commands[command]
  },

  loadConfig: function (config) {
    this.config = config
  },

  begin: function (element) {
    var parentElement = element || document.body

    this.div = document.createElement('div')
    this.div.classList.add('jsterm')
    parentElement.appendChild(this.div)

    const preventDefaultFrom = [
      'Backspace',
      'Tab',
      'Enter',
      'Delete',
      'ArrowUp',
      'ArrowDown'
    ]

    window.onkeydown = function (e) {
      if (preventDefaultFrom.includes(e.key)) {
        e.preventDefault()
      }

      this._handleSpecialKey(e.key, e)
      this._typeKey(e.key, e)
    }.bind(this)

    this.returnHandler = this._execute
    this.cwd = this.fs
    this._prompt()
    this._toggleBlinker()
    this._dequeue()
  },

  getCWD: function () {
    return this.dirString(this.cwd)
  },

  dirString: function (d) {
    var dir = d,
      dirStr = ''

    while (this._dirNamed('..', dir.contents).contents !== dir.contents) {
      dirStr = '/' + dir.name + dirStr
      dir = this._dirNamed('..', dir.contents)
    }
    return dirStr || '/'
  },

  getEntry: function (path) {
    var entry, parts

    if (!path) return null

    path = path.replace(/^\s+/, '').replace(/\s+$/, '')
    if (!path.length) return null

    entry = this.cwd
    if (path[0] == '/') {
      entry = this.fs
      path = path.substring(1, path.length)
    }

    parts = path.split('/').filter(function (x) {
      return x
    })
    for (var i = 0; i < parts.length; ++i) {
      entry = this._dirNamed(parts[i], entry.contents)
      if (!entry) return null
    }

    return entry
  },

  write: function (text, type) {
    var output = this.stdout()

    if (!output) return
    output.innerHTML += text
    if (type) output.classList.add(type)
  },

  defaultReturnHandler: function () {
    this.returnHandler = this._execute
  },

  typeCommand: function (command, cb) {
    var that = this

    ;(function type(i) {
      if (i == command.length) {
        that._handleSpecialKey('Enter')
        if (cb) cb()
      } else {
        that._typeKey(command[i])
        setTimeout(function () {
          type(i + 1)
        }, 50)
      }
    })(0)
  },

  tabComplete: function (text) {
    var parts = text.replace(/^\s+/, '').split(' '),
      matches = []
    if (!parts.length) return []

    if (parts.length == 1) {
      // TODO: Combine with below.
      var pathParts = parts[0].replace(/[\/]+/, '/').split('/'),
        last = pathParts.pop(),
        dir = pathParts.length > 0 ? this.getEntry(pathParts.join('/')) : this.cwd,
        n,
        fullPath,
        last,
        dir

      if (dir) {
        for (var i in dir.contents) {
          n = dir.contents[i].name
          if (n.startsWith(last) && !n.startsWith('.') && n != last) {
            if (dir.contents[i].type == 'exec') matches.push(n + ' ')
          }
        }
      }
      for (var c in this.commands) {
        // Private member.
        if (c[0] == '_') continue
        if (c.startsWith(parts[0]) && c != parts[0]) matches.push(c + ' ')
      }
    } else {
      fullPath = parts[parts.length - 1]
      pathParts = fullPath.replace(/[\/]+/, '/').split('/')
      last = pathParts.pop()
      dir = pathParts.length > 0 ? this.getEntry(pathParts.join('/')) : this.cwd

      if (!dir) return []

      for (var i in dir.contents) {
        n = dir.contents[i].name
        if (n.startsWith(last) && !n.startsWith('.') && n != last) {
          if (dir.contents[i].type == 'dir') matches.push(n + '/')
          else matches.push(n + ' ')
        }
      }
    }
    return matches
  },

  enqueue: function (command) {
    this._queue.push(command)
    return this
  },

  scroll: function () {
    window.scrollTo(0, document.body.scrollHeight)
  },

  parseArgs: function (argv) {
    var args = [],
      filenames = [],
      opts

    for (var i = 0; i < argv.length; ++i) {
      if (argv[i].startsWith('-')) {
        opts = argv[i].substring(1)
        for (var j = 0; j < opts.length; ++j) args.push(opts.charAt(j))
      } else {
        filenames.push(argv[i])
      }
    }
    return { filenames: filenames, args: args }
  },

  writeLink: function (e, str) {
    this.write('<span class="' + e.type + '">' + this._createLink(e, str) + '</span>')
  },

  stdout: function () {
    return this.div.querySelector('#stdout')
  },

  newStdout: function () {
    var stdout = this.stdout(),
      newstdout = document.createElement('span')

    this._resetID('#stdout')
    newstdout.id = 'stdout'
    stdout.parentNode.insertBefore(newstdout, stdout.nextSibling)
    this._toggleBlinker()
  },

  _createLink: function (entry, str) {
    function typeLink(text, link) {
      return '<a href="javascript:void(0)" onclick="typeCommand(\'' + text + '\')">' + link + '</a>'
    }

    if (entry.type == 'dir' || entry.type == 'link') {
      return typeLink('ls -l ' + str, entry.name)
    } else if (entry.type == 'text' || entry.type == 'textFile') {
      return typeLink('cat ' + str, entry.name)
    } else if (entry.type == 'img') {
      return typeLink('gimp ' + str, entry.name)
    } else if (entry.type == 'exec') {
      return '<a href="' + entry.contents + '" target="_blank">' + entry.name + '</a>'
    }
  },

  _dequeue: function () {
    if (!this._queue.length) return

    this.typeCommand(
      this._queue.shift(),
      function () {
        this._dequeue()
      }.bind(this)
    )
  },

  _dirNamed: function (name, dir) {
    for (var i in dir) {
      if (dir[i].name == name) {
        if (dir[i].type == 'link') return dir[i].contents
        else return dir[i]
      }
    }
    return null
  },

  _addDirs: function (curDir, parentDir) {
    curDir.contents.forEach(
      function (entry, i, dir) {
        if (entry.type == 'dir') this._addDirs(entry, curDir)
      }.bind(this)
    )
    curDir.contents.unshift({
      name: '..',
      type: 'link',
      contents: parentDir,
    })
    curDir.contents.unshift({
      name: '.',
      type: 'link',
      contents: curDir,
    })
  },

  _toggleBlinker: function () {
    var blinker = this.div.querySelector('#blinker'),
      stdout

    if (blinker) {
      blinker.parentNode.removeChild(blinker)
    }

    stdout = this.stdout()
    if (stdout) {
      blinker = document.createElement('span')
      blinker.id = 'blinker'
      blinker.innerHTML = '&#x2588'
      stdout.parentNode.appendChild(blinker)
    }
  },

  _resetID: function (query) {
    var element = this.div.querySelector(query)

    if (element) element.removeAttribute('id')
  },

  _prompt: function () {
    var div = document.createElement('div'),
      prompt = document.createElement('span'),
      command = document.createElement('span')

    this._resetID('#promptWrapper')
    div.id = 'promptWrapper'

    this._resetID('#currentPrompt')
    this.div.appendChild(div)

    prompt.classList.add('prompt')
    prompt.id = 'currentPrompt'
    prompt.innerHTML = this.config.prompt(this.getCWD(), this.config.username)
    div.appendChild(prompt)

    this._resetID('#stdout')
    command.classList.add('command')
    command.id = 'stdout'
    div.appendChild(command)
    this._toggleBlinker()
    this.scroll()
  },

  _typeKey: function (key, e) {
    e = e || {}
    var stdout = this.stdout()

    // Relies on all non-printable special keys being represented as strings longer than 1
    if (!stdout || key.length > 1 || e.ctrlKey) return

    stdout.innerHTML += key
  },

  _handleSpecialKey: function (key, e) {
    e = e || {}
    var stdout = this.stdout(),
      parts,
      pathParts

    if (!stdout) return
    // Backspace/delete.
    if (key === 'Backspace' || key == 'Delete') stdout.innerHTML = stdout.innerHTML.replace(/.$/, '')
    // Enter.
    else if (key === 'Enter') this.returnHandler(stdout.innerHTML)
    // Up arrow.
    else if (key === 'ArrowUp') {
      if (this._historyIndex < this._history.length - 1)
        stdout.innerHTML = this._history[++this._historyIndex]
      // Down arrow.
    } else if (key === 'ArrowDown') {
      if (this._historyIndex <= 0) {
        if (this._historyIndex == 0) this._historyIndex--
        stdout.innerHTML = ''
      } else if (this._history.length) stdout.innerHTML = this._history[--this._historyIndex]
      // Tab.
    } else if (key === 'Tab') {
      const matches = this.tabComplete(stdout.innerHTML)
      if (matches.length) {
        parts = stdout.innerHTML.split(' ')
        pathParts = parts[parts.length - 1].split('/')
        pathParts[pathParts.length - 1] = matches[0]
        parts[parts.length - 1] = pathParts.join('/')
        stdout.innerHTML = parts.join(' ')
      }
      // Ctrl+C, Ctrl+D.
    } else if ((key === 'c' || key === 'd') && e.ctrlKey) {
      if (key === 'c') this.write('^C')
      this.defaultReturnHandler()
      this._prompt()
      // Ctrl+L
    } else if (key === 'l' && e.ctrlKey) {
      var buffer = stdout.innerHTML
      this.commands.clear(
        [],
        function () {
          this._prompt()
          stdout.innerHTML = buffer
        }.bind(this)
      )
      // Ctrl+V
    } else if (key === 'v' && e.ctrlKey) {
      navigator.clipboard.readText()
        .then((text) => {
          this.write(text)
        })
    }
  },

  _execute: function (fullCommand, skipPrompt = false) {
    var output = document.createElement('div'),
      stdout = document.createElement('span'),
      parts = fullCommand.split(' ').filter(function (x) {
        return x
      }),
      command = parts[0],
      args = parts.slice(1, parts.length),
      entry = this.getEntry(fullCommand)

    this._resetID('#stdout')
    stdout.id = 'stdout'
    output.appendChild(stdout)
    this.div.appendChild(output)

    if (command && command.length) {
      if (command in this.commands) {
        this.commands[command](
          args,
          function () {
            this.defaultReturnHandler()
            !skipPrompt && this._prompt()
          }.bind(this)
        )
      } else if (entry && entry.type == 'exec') {
        window.open(entry.contents, '_blank')
        !skipPrompt && this._prompt()
      } else {
        this.write(command + ': command not found')
        !skipPrompt && this._prompt()
      }
    } else {
      this._prompt()
    }
    if (fullCommand.length) this._history.unshift(fullCommand)
    this._historyIndex = -1
  },
}

var term = Object.create(Terminal)

window.typeCommand = function (command) {
  term.typeCommand(command)
}

export default term
