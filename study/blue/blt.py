#!/usr/bin/python2

from bluetooth import bluez

target_name ="My Phone"
target_address =None

nearby_devices =bluez.discover_devices()

for bdaddr in nearby_devices :
     print bdaddr, bluez.lookup_name( bdaddr ) 
     if target_name == bluez.lookup_name( bdaddr ) :
         target_address = bdaddr
         break

if target_address is not None :
    print "found target bluetooth device with address ", taregt_address
else :
    print "could not find target bluetooth device nearby"

