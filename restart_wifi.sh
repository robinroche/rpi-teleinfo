#!/bin/bash                                                           
  
ping -c4 www.google.com > /dev/null               
  
if [ $? != 0 ]                               
then                                         
    echo "wifi seems down, restarting"
    ifdown --force wlan0                     
    ifup wlan0                               
else                                        
    echo "wifi seems up"           
fi
