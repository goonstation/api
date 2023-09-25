## Death

---

This represents the death of a player.

### Structure

```json
{
    "type": "death",
    "round_id": "integer",
    "player_id": "integer",
    "created_at": "string",
    "data": {
        "mob_name": "string",
        "mob_job": "string",
        "x": "integer",
        "y": "integer",
        "z": "integer",
        "bruteloss": "integer",
        "fireloss": "integer",
        "toxloss": "integer",
        "oxyloss": "integer",
        "gibbed": "boolean",
        "last_words": "string",
    }
}
```
