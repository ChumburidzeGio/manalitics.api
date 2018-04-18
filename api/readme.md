### Transactions
After uploading the new bank statement by the user, we just normalize data and add to the database.

The fields that should be present are:
```Title, Date, Description, Type, Amount, Currency, Is Expense, Original```

#### Transaction types
- __pay_terminal__ = Card payment on POS terminals
- __online_banking__ - Transfers made from online bank
- __transfer__ - Something like a getting money on account
- __debt_collection__ - Automatic payments for subscriptions or scheduled payments such as communal bills
- __atm__ Cash withdrawal from ATM
- __miscellaneous__ - Anything that can't be add to categories above
            
We check if all merchants are in DB. After that, we make a lookup for new merchants to add to them google place id and tags.

Transactions with type pay_terminal to be categorized based on tags. 

#### Categories
- HB - Home and bills
- HE - Health and beauty
- ET - Entertainment and travel
- BE - Basic Expense
- CF - Clothing and footwear
- TC - Transport and Car
- ED - Education
- AT - Cash
- TA - Internal transfers
- FI - Finances (bank and etc.)
- OT - Others

So in the end, we will have transactions with fields above + ```tags, category```.

Transactions are attached to one user and they have bank identifier.

### Merchants

Merchants will have title, pos_id, category_id and is_generic.

If merchant has is_generic tag it means that pos_id is regex string, 
f.e. normal pos_id can be ```PYRA BAR STARE MIASTO POZNAN``` and generic ```/(uber)(.*)(help)(?=.*)/gi```

### Analytics
- __Unnecessary fees__ - Show the fees that you payed and you could avoid f.e. ATM fees and suggestions based on previous withdrawing where it was without fee.
- __Where money goes__ - See for what you spend how much and how much you spent in average in past months and how much was difference, f.e. for eating out this month you spent 400 euros and pas month 80 so 320 euros exceeded

### Recommendations
- __Subscription__ - Get recommendations for services you use (if there are cheaper ones)

### Alerts
- __Subscription price change__ - Get notified when price changes for subscriptions you have