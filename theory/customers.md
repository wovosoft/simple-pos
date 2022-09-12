##  Customers

Customers are persons who purchase products from the seller.

## Table Fields

| Attribute   | Type   | Constraints | Default | description |
| ----------- | ------ | ----------- | ------- | ----------- |
| name        | string | required    |         |             |
| phone       | string | nullable    |         |             |
| address     | string | nullable    |         |             |
| description | string | nullable    |         |             |
| payable     | double | required    | 0       |             |

## payable

This field is dynamic. Updated on each  sale and return etc.

- When product is sold to customer, payable is increased.
- When product is returned from customer, payable is decreased.
- Other fields like total sold amount can be stored in separate table, if 
  closing features is implemented. Actually this feature should be implemented.

## Dataflow

From top to bottom in asc order

1. When Sale is created, Payable amount is added to the associated customer's account.
2. When Payment for a customer is added, payable amount is decreased.
3. When products returned, payable amount is decreased by sum of returned amount.

