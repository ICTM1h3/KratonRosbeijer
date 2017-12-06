# KratonRosbeijer
The new website for restaurant Kraton Rosbeijer

## Set up email

1. Open C:\XAMPP\php.ini
2. Change the email values to the following: 
   ```
   SMTP=smtp.gmail.com
   smtp_port=587
   sendmail_from=ictmwindesheim@gmail.com
   sendmail_path="\"C:\xampp\sendmail\sendmail.exe\" -t"
   ```
3. Open C:\XAMPP\sendmail\sendmail.ini
4. Change the values to the following:
   ```
   smtp_server=smtp.gmail.com
   smtp_port=465
   smtp_ssl=ssl
   auth_username=ictmwindesheim@gmail.com
   force_sender=ictmwindesheim@gmail.com
   auth_password=<Ask someone for the password>
   ```
5. PROFIT!
