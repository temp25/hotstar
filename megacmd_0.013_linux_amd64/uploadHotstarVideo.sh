#!/bin/sh 

a=0 
chmod +x megacmd
while [ $a -lt 50 ] 
   do  
      a=`expr $a + 1`
      #echo $a
      uploadQuery="./megacmd -conf megacmd.json put \"/app/hotstarVideo/Episode_$a.ts\" mega:/"
      echo $uploadQuery
      eval $uploadQuery
   done