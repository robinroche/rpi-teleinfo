import serial
import time
import string
import urllib2
import subprocess

ser = serial.Serial('/dev/ttyAMA0', baudrate=1200, bytesize=serial.SEVENBITS, parity=serial.PARITY_EVEN, stopbits=serial.STOPBITS_ONE, timeout=0, rtscts=1)
found = 0
found_papp = 0
found_ptec = 0
use_hphc = 0

# TODO: avoid infinite loop
while found==0:
	linecont = ser.readline()
	if not linecont:
		print 'Empty: check wiring if multiple occurences'
	# else:
	#	print linecont // for debugging only
	
	# TODO: add checksum check
	
	if (string.find(linecont,'PAPP')!=-1 and found_papp==0):
		try:
			papp = int(linecont[5:10])
			if papp>=1:
				found_papp = 1
				print 'papp found:'
				print papp
		except ValueError:
			print 'error'
	
	if (use_hphc==1):
		if (string.find(linecont,'PTEC')!=-1 and found_ptec==0):
			hphc = linecont[5:7]
			if (hphc=='HC' or hphc=='HP'):
				found_ptec = 1
				print 'hphc found:'
				print hphc
	else:
		hphc = 'HP';
		found_ptec = 1;
		
	found = found_papp*found_ptec
	time.sleep(1)

url = "http://urlduserveur/upload_data.php?PASSWD=123456&LOAD=" + str(papp) + "&HPHC=" + str(hphc)
print 'Connecting to ' + url
try:
	response = urllib2.urlopen(url,timeout=5).read()
	print 'Return: ' + response
except:
	print 'Error: wifi probably down - Restarting wifi'
	subprocess.call('./restart_wifi.sh', shell=True)
