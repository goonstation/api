## Antag Item Purchase

---

This represents the purchase of an item by an antag.

### Structure

```json
{
    "type": "antag_item_purchase",
    "round_id": "integer",
    "player_id": "integer",
    "created_at": "string",
    "data": {
        "mob_name": "string",
        "mob_job": "string",
        "x": "integer",
        "y": "integer",
        "z": "integer",
        "item": "string",
        "cost": "integer"
    }
}
```
