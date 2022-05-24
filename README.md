## Clone the Application

```
git clone [https://github.com/Werfmon/blog-backend.git](https://github.com/Werfmon/blog-backend.git)
```


### First method
To run the application in development, you can run these commands 

```bash
cd blog-backend
composer start
```
After that, open [http://localhost:8080](http://localhost:8080) in your browser.

### Second method

You can use your apache server, you need to setup vlans, for example like this:

directory of file: C:\xampp\apache\conf\extra\httpd-vhosts

```xml
<VirtualHost *:80>
    ServerName blog.local
    DocumentRoot "C:\xampp\htdocs\blog-backend\public" // path to public folder where is loakted index.php
    <Directory "C:\xampp\htdocs\blog-backend">
    </Directory>
</VirtualHost>
```

Also you need map this address in: C:\Windows\System32\drivers\etc\hosts (in windows you need to open a editor as admin)
Add same name as ServerName in httpd-vhosts
```
  ...
  127.0.0.1 blog.local
  ...
```

