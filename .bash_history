sudo apt-get install apache2
sudo nano /etc/apache2/apache2.conf
sudo service apache2 restart
sudo a2dissite default
cd ~
mkdir public
mkdir -p public/periscope
ls
cd public
ls
cd..
cd ..
ls
sudo chmod a+rx
sudo chmod a+rx ~
sudo chmod -R a+rx ~/public
sudo apt-get install mysql
apt-cache search mysql
apt-cache search mysql-server
sudo apt-get install mysql-server
man scp
scp /home/michaeltaggart/Documents/periscope.tar.gz  /home/mttaggart
exit
scp
man scp
scp /home/michaeltaggart/Documents/periscope.tar.gz ~
exit
sudo apt-cache search phpmyadmin
sudo apt-get install phpmyadmin
ls
tar --help
sudo tar -xf periscope.tar.gz
ls
cd periscope
ls
sudo -i
sudo cp -R /var/www/periscope /home/mttaggart/periscope
sudo git add periscope
sudo git commit -m 'upload fix'
sudo -i
