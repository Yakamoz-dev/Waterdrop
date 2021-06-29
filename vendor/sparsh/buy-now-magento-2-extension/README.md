#Buy Now Module
The Buy Now extension for Magento 2 developed by Sparsh allows us to have a "Buy Now" button to directly go to the checkout page to speed up the checkout process and increase conversion rate on your Magento store.

##Support: 
version - 2.3.x

##How to install Extension

1. Download the archive file.
2. Unzip the file
3. Create a folder [Magento_Root]/app/code/Sparsh/BuyNow
4. Drop/move the unzipped files to directory '[Magento_Root]/app/code/Sparsh/BuyNow'

#Enable Extension:
- php bin/magento module:enable Sparsh_BuyNow
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush

#Disable Extension:
- php bin/magento module:disable Sparsh_BuyNow
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush