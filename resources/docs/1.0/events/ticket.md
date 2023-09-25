## Ticket

---

This represents a ticket issued by security against a player.

### Structure

```json
{
    "type": "ticket",
    "round_id": "integer",
    "player_id": "integer",
    "created_at": "string",
    "data": {
        "target": "string",
        "reason": "string",
        "issuer": "string",
        "issuer_job": "string",
        "issuer_ckey": "string"
    }
}
```
