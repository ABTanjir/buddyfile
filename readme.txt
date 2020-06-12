Buddy File Plugin
------------------


First of all if a user is not purchased any product and not an admin plugin redirect him to home page

If a user bought any product plugin will get his user id and get all products id purchased by him
using purchased_products() function;

It will then get all the buyer list who bought the same product 
using buyer_by_product_id() function 

and finally it will fetch all files uploaded by the buyers who have common purchase product id
using all_buyer_files() function
if logged in user is an admin all_buyer_files() function will returl all file link uploaded by any users.

used google drive api,

so when user upload a video file it will check the file size from client side and as well as server side if the file size 
is in between 20mb and 200mb it will alco check file mime type is the selected file is video file or not
and if file is validated the file will be uploaded to google drive via api, and it will store the video download link to table named [buddy_files];

[buddy_file] table will store file's direct download link, and user id.

----- For Your Quick Testing -----
I have Provided A Gmail Account's Credential in drive/credentials/client_secret.json

------------------------------------------------------------------------------------------------------------------
I think I should create a plugin setup page from where admin will able to connect and configure his own google drive API
but for now you can follow this simple steps:

1. Go to this link
https://developers.google.com/drive/api/v3/quickstart/go?authuser=1

2. Enable Drive API

3. Download The credential.js file

4. paste the file's content in client_secret.json



Thank you