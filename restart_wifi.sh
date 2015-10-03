#!/bin/bash                                                           
  
ping -c4 www.google.com > /dev/null               
  
if [ $? != 0 ]                               
then                                         
    logger -t $0 "wifi seems down, restarting"
    ifdown --force wlan0                     
    ifup wlan0                               
else                                        
    logger -t $0 "wifi seems up"           
fi               
