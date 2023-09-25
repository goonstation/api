## Fine

---

This represents a fine issued by security against a player.

### Structure

```json
{
    "type": "fine",
    "round_id": "integer",
    "player_id": "integer",
    "created_at": "string",
    "data": {
        "target": "string",
        "reason": "string",
        "issuer": "string",
        "issuer_job": "string",
        "issuer_ckey": "string",
        "amount": "integer"
    }
}
```
