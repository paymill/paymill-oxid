PAYMILL - Oxid 4.7
==================

Payment plugin for Oxid Version 4.7.x (ce, pe) and 4.8.x (ce, pe)

Download the module here: https://github.com/Paymill/Paymill-Oxid-4.7/archive/master.zip

- Merge the content with your Oxid installation.
- Import the install.sql to your database.
- In your administration backend activate the PAYMILL plugin.
- Go to the configuration section where you can insert your private and public key (that you can find in your PAYMILL cockpit [https://app.paymill.de/](https://app.paymill.de/ "Paymill cockpit")).

# Activate PAYMILL Payment

To activate PAYMILL payment follow these steps:

- In the main menu goto **Shopeinstellungen > Zahlungsarten**
- Choose the payment method you want to activate
- Click on **Benutzergruppen zuordnen** and assign the right user groups
- Go to tab **Länder**, click on **Länder zuordnen**, and assign the right countries
- In the main menu goto **Shopeinstellungen > Versandarten**
- Choose a shipping type (e.g. **Standard**) and go to tab **Zahlungsarten**
- Click on **Zahlungsarten zuordnen** and assign the payment method
- Repeat last 2 steps for other shipping types

# Update
If you want to update from an version earlier than 2.1 you have to run the update.sql first.

# Template-Support

- Azure-template is supported by default.
- To support a custom template adapt the template structure within the out/azure diretory to your custom theme.

# Error handling

In case of any errors turn on the debug mode in the PAYMILL payment method configuration.
Open the javascript console in your browser and check what's being logged during the checkout process.

# Logging

- If you enable logging in the plugin configuration make sure that log.txt inside the plugin directory is writable. Otherwise logging information will not be stored to the logfile.
- You can access the Logging with your shop-backend under PAYMILL -> Log

# Notes about the payment process

The payment is processed when an order is placed in the shop frontend.

Fast Checkout: Fast checkout can be enabled by selecting the option in the PAYMILL Basic Settings. If any customer completes a purchase while the option is active this customer will not be asked for data again. Instead a reference to the customer data will be saved allowing comfort during checkout.
