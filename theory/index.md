## Discussion 

This POS version is being developed to overcome the problems with

- exact profit and loss calculation
- code reusabality
- customers and suppliers wallet
- fix memory dump issues, and performance issues with big data aggregations like sum, count etc.
- integrate sms/email notifications.
- develop native mobile application

## Solution

To fix issues with exact profit and losses, product items will be added from purchase items, and the product items will be grouped by
purchase items. products won't have direct quantity, rather will be
calculated from product quantities. 

Sales and purchase items won't have direct modification features. because this feature causes mismatch of customers/suppliers previous balance and current balance.