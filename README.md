# Peoples Pay API
## Introduction
Peoples Pay API allows seamless integration with various Mobile Money wallets
in Ghana.

## Getting Started
To get start, require the package with composer.

`composer require appiersign/peoples-pay`

You will need a `$merchantId`, `$baseUrl`, and `$apiKey`.

## Usage
Get perform any action, you will need an instance of the PeoplesPay class. See below for example:

`$peoplesPay = new PeoplesPay($baseUrl, $apiKey, $merchantId);`

## Get Account Holder Name

Fetching account holder's name is simple. All you have to do is to call the `getMobileMoneyAccountName()` see below for example:

`$response = $peoplesPay->getMobileMoneyAccountName($phoneNumber, $network);`

Sample response:

`['success' => true, 'code' => '00', 'message' => 'success', 'data' => 'Account Holder']`
## Make Payment
To make payment or collect money from mobile money wallet
- create an instance of the PeoplesPay class

`$peoplesPay = new PeoplesPay($baseUrl, $apiKey, $merchantId);`

- call the `collectMobileMoney()` on the instance

`$response = $peoplesPay->collectMobileMoney($phoneNumber, $network', $amount, $transactionReference, $callbackUrl, $description));`

A sample response looks like this:

`['code' => '01', 'success' => true, 'message' => 'Transaction Received for Processing', 'transactionId' => 'xxxxxxxxxxxxxxx', 'date' => '2022-06-10T11:10:11.547Z']`


## Checking Payment Status

To check the status of a transaction, you will need the `$transactionId` that was returned with the response above. You can then call the `checkStatus()` on the `$peoplesPay` instance like this:

`$response = $peoplesPay->checkStatus($transactionId);`

See sample response below:

`['success' => true, 'code' => '00', 'message' => 'Transaction Successful', 'status' => 'paid', 'transactionId' => $transactionId, 'issuerId' => 'xxxxxxxxxx]`

## Disbursement
Coming soon!!!