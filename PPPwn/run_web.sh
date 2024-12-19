#!/bin/bash

if [ -f /tmp/ps4alreadypwned.txt ]; then exit 0; fi

if [ -f /boot/firmware/PPPwn/config.sh ]; then
source /boot/firmware/PPPwn/config.sh
fi
if [ -z $CPPMETHOD ]; then CPPMETHOD="3"; fi
if [ -z $INTERFACE ]; then INTERFACE="eth0"; fi
if [ -z $FIRMWAREVERSION ]; then FIRMWAREVERSION="11.00"; fi
if [ -z $USBETHERNET ]; then USBETHERNET=false; fi
if [ -z $STAGE2METHOD ]; then STAGE2METHOD="flow"; fi
if [ -z $SOURCEIPV6 ]; then SOURCEIPV6="2"; fi
if [ -z $CUSTOMIPV6 ]; then CUSTOMIPV6="9f9f:41ff:9f9f:41ff"; fi
if [ -z $DETECTMODE ]; then DETECTMODE="2"; fi

if [ -z $PPPOECONN ]; then PPPOECONN=true; fi
if [ -z $PWNAUTORUN ]; then PWNAUTORUN=false; fi
if [ -z $TIMEOUT ]; then TIMEOUT="5m"; fi
if [ -z $PPDBG ]; then PPDBG=false; fi

shutdown_device () {
	coproc read -t 5 && wait "$!" || true
	sudo ip link set $INTERFACE down
	coproc read -t 2 && wait "$!" || true
	sudo poweroff
	coproc read -t 2 && wait "$!" || true
	sudo shutdown -P now
}

stop_pppoe () {
	sudo systemctl stop pppoe
	sudo /etc/init.d/nginx stop
	sudo killall pppoe-server
	if [ -f /boot/firmware/PPPwn/pwn.log ]; then
		sudo rm -f /boot/firmware/PPPwn/*.log
	fi
}

start_pppoe () {
	sudo /etc/init.d/nginx start
	sudo systemctl start pppoe
	sudo ip link set $INTERFACE up
}

if [[ $DETECTMODE == "3" ]] || [[ $DETECTMODE == "4" ]] ;then
if [[ ${STAGE2METHOD,,} == "goldhen" ]] || [[ ${STAGE2METHOD,,} == *"gold"* ]] ;then
if [[ -z $1 ]]; then
	start_pppoe
	coproc read -t 2 && wait "$!" || true
fi	
ghcounter=0
while [[ $(sudo nmap -p 3232 192.168.2.2 | grep '3232/tcp' | cut -f2 -d' ') == "" ]]
do
	coproc read -t 2 && wait "$!" || true
	if [[ $((ghcounter++)) -eq 10 ]]; then
		sudo reboot
	fi
done
coproc read -t 5 && wait "$!" || true
GHT=$(sudo nmap -p 3232 192.168.2.2 | grep '3232/tcp' | cut -f2 -d' ')
if [[ $GHT == *"close"* ]] ; then
	coproc read -t 2 && wait "$!" || true
	GHT=$(sudo nmap -p 3232 192.168.2.2 | grep '3232/tcp' | cut -f2 -d' ')
fi
if [[ $GHT == *"open"* ]] ; then
	echo -e "\n\033[95mGoldhen found aborting pppwn\033[0m\n" | sudo tee /dev/tty1
	if [ $PPPOECONN = true ] ; then
		echo 'GoldHEN' | sudo tee /tmp/ps4alreadypwned.txt
		if [[ -z $1 ]]; then
			start_pppoe
			coproc read -t 2 && wait "$!" || true
		fi
		exit 0
	else
		shutdown_device
	fi
else
echo -e "\n\033[95mGoldhen not found\033[0m\n" | sudo tee /dev/tty1
fi
else
echo -e "\n\033[95mHEN detection not support\033[0m\n" | sudo tee /dev/tty1
fi
fi

if [[ -z $1 ]]; then
	if [ $PPPOECONN = true ] ; then
		if [ $PWNAUTORUN = true ] ; then
			DETECTLINK=true
			stop_pppoe
		else
			start_pppoe
			exit 0
		fi
	else
		DETECTLINK=true
		stop_pppoe
	fi
else
	DETECTLINK=false
	stop_pppoe
fi

PITYP=$(tr -d '\0' </proc/device-tree/model) 
if [[ $PITYP == *"Raspberry Pi 2"* ]] ;then
CPPBIN="pppwn7"
elif [[ $PITYP == *"Raspberry Pi 3"* ]] ;then
CPPBIN="pppwn64"
elif [[ $PITYP == *"Raspberry Pi 4"* ]] ;then
CPPBIN="pppwn64"
elif [[ $PITYP == *"Raspberry Pi Compute Module 4"* ]] ;then
CPPBIN="pppwn64"
elif [[ $PITYP == *"Raspberry Pi 5"* ]] ;then
CPPBIN="pppwn64"
elif [[ $PITYP == *"Raspberry Pi Zero 2"* ]] ;then
CPPBIN="pppwn64"
elif [[ $PITYP == *"Raspberry Pi Zero"* ]] ;then
CPPBIN="pppwn11"
elif [[ $PITYP == *"Raspberry Pi"* ]] ;then
CPPBIN="pppwn11"
else
CPPBIN="pppwn64"
fi
arch=$(getconf LONG_BIT)
if [ $arch -eq 32 ] && [ $CPPBIN = "pppwn64" ] && [[ ! $PITYP == *"Raspberry Pi 4"* ]] && [[ ! $PITYP == *"Raspberry Pi 5"* ]] ; then
CPPBIN="pppwn7"
fi

STAGE1F="/boot/firmware/PPPwn/stage1/stage1_${FIRMWAREVERSION//.}.bin"

if [[ ${STAGE2METHOD,,} == "goldhen" ]] || [[ ${STAGE2METHOD,,} == *"gold"* ]] ;then
STAGE2PATH="goldhen"
elif [[ ${STAGE2METHOD,,} == "hen" ]] || [[ ${STAGE2METHOD,,} == *"vtx"* ]] ;then
STAGE2PATH="vtxhen"
elif [[ ${STAGE2METHOD,,} == "bestpig" ]] || [[ ${STAGE2METHOD,,} == *"pig"* ]] ;then
STAGE2PATH="bestpig"
else
STAGE2PATH="Hey, joe97tab why not add new stage2"
fi

if [ -f /boot/firmware/PPPwn/stage2/$STAGE2PATH/stage2_${FIRMWAREVERSION//.}.bin ] ; then
STAGE2F="/boot/firmware/PPPwn/stage2/$STAGE2PATH/stage2_${FIRMWAREVERSION//.}.bin"
if [[ $STAGE2PATH == "goldhen" ]] ; then
XFGH="-gh"
else
XFGH=""
fi
else
STAGE2F="/boot/firmware/PPPwn/stage2/TheOfficialFloW/stage2_${FIRMWAREVERSION//.}.bin"
XFGH=""
fi

if [[ $CPPMETHOD == *"1"* ]] || [[ ${CPPMETHOD,,} == *"v1"* ]] ;then
CPPBIN+='v1'
CPPNAME="v1.0.0 xfangfang c++ binary"
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --stage1 "$STAGE1F" --stage2 "$STAGE2F""
else
if [ -f /boot/firmware/PPPwn/pconfig.sh ]; then
source /boot/firmware/PPPwn/pconfig.sh
fi
if [ -z $XFWAP ]; then XFWAP="1"; fi
if [ -z $XFGD ]; then XFGD="4"; fi
if [ -z $XFBS ]; then XFBS="0"; fi
if [ -z $XFNWB ]; then XFNWB=false; fi
if [ -z $SPRAY_NUM ]; then SPRAY_NUM="1000"; fi
if [ -z $CORRUPT_NUM ]; then CORRUPT_NUM="1"; fi
if [ -z $PIN_NUM ]; then PIN_NUM="1000"; fi
if [[ $SOURCEIPV6 == "3" ]] ; then
if [[ $CUSTOMIPV6 == "" ]] ; then
XFIP="fe80::9f9f:41ff:9f9f:41ff"
else
XFIP="fe80::"
XFIP+=$CUSTOMIPV6
fi
else
if [[ $SOURCEIPV6 == "1" ]] ; then
XFIP="fe80::4141:4141:4141:4141"
else
XFIP="fe80::9f9f:41ff:9f9f:41ff"
fi
fi
if [ $XFNWB = true ] ; then
XFNW="--no-wait-padi"
else
XFNW=""
fi
if [[ $((XFWAP)) -lt 1 ]] || [[ $((XFWAP)) -gt 20 ]]; then XFWAP="1"; fi
if [[ $((XFGD)) -lt 1 ]] || [[ $((XFGD)) -gt 4097 ]]; then XFGD="4"; fi
if [[ $((XFBS)) -lt 0 ]] || [[ $((XFBS)) -gt 20480 ]]; then XFBS="0"; fi
if [[ $CPPMETHOD == *"2"* ]] || [[ ${CPPMETHOD,,} == *"s"* ]] ;then
CPPNAME="stooged c++ binary"
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --ipv "$XFIP" --wait-after-pin $XFWAP --groom-delay $XFGD --buffer-size $XFBS $XFNW $XFGH"
elif [[ $CPPMETHOD == *"4"* ]] || [[ ${CPPMETHOD,,} == *"n"* ]] ;then
CPPBIN+='nn9dev'
CPPNAME="nn9dev c++ binary 1.2b1"
if [[ $((SPRAY_NUM)) -lt 400 ]] || [[ $((SPRAY_NUM)) -gt 1500 ]]; then SPRAY_NUM="1000"; fi
if [[ $((CORRUPT_NUM)) -lt 1 ]] || [[ $((CORRUPT_NUM)) -gt 40 ]]; then CORRUPT_NUM="1"; fi
if [[ $((PIN_NUM)) -lt 1000 ]] || [[ $((PIN_NUM)) -gt 2000 ]]; then PIN_NUM="1000"; fi
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --ipv6 "$XFIP" --stage1 "$STAGE1F" --stage2 "$STAGE2F" --spray-num 0x$SPRAY_NUM --corrupt-num 0x$CORRUPT_NUM --pin-num 0x$PIN_NUM --wait-after-pin $XFWAP --groom-delay $XFGD --buffer-size $XFBS $XFNW"
else
if [[ $SOURCEIPV6 == "1" ]] ; then
CPPBIN+='old'
else
CPPBIN+='new'
fi
CPPNAME="latest xfangfang c++ binary"
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --stage1 "$STAGE1F" --stage2 "$STAGE2F" --wait-after-pin $XFWAP --groom-delay $XFGD --buffer-size $XFBS $XFNW"
fi
fi
PPPwnPS4="$(echo -e "${PPPwnPS4}" | sed -e 's/[[:space:]]*$//')"

echo -e "\n\n\033[36m _____  _____  _____                 
|  __ \\|  __ \\|  __ \\
| |__) | |__) | |__) |_      ___ __
|  ___/|  ___/|  ___/\\ \\ /\\ / / '_ \\
| |    | |    | |     \\ V  V /| | | |
|_|    |_|    |_|      \\_/\\_/ |_| |_|\033[0m
\n\033[33mhttps://github.com/TheOfficialFloW/PPPwn\033[0m\n" | sudo tee /dev/tty1

echo -e "\033[37mGoldhen by      : SiSTR0\033[0m" | sudo tee /dev/tty1
echo -e "\033[37mHen by          : EchoStretch and BestPig\033[0m" | sudo tee /dev/tty1
echo -e "\033[37mOriginal Script : Stooged\033[0m" | sudo tee /dev/tty1
echo -e "\033[37mC++ Port        : xfangfang\033[0m" | sudo tee /dev/tty1
echo -e "\033[37mMod By          : joe97tab\033[0m" | sudo tee /dev/tty1

if [ $USBETHERNET = true ] ; then
echo '1-1' | sudo tee /sys/bus/usb/drivers/usb/unbind >/dev/null
coproc read -t 1 && wait "$!" || true
echo '1-1' | sudo tee /sys/bus/usb/drivers/usb/bind >/dev/null
coproc read -t 2 && wait "$!" || true
sudo ip link set $INTERFACE up
coproc read -t 1 && wait "$!" || true
else
sudo ip link set $INTERFACE down
coproc read -t 2 && wait "$!" || true
sudo ip link set $INTERFACE up
coproc read -t 1 && wait "$!" || true
fi

echo -e "\n\033[36m$PITYP\033[92m\nFirmware:\033[93m $FIRMWAREVERSION\033[92m\nInterface:\033[93m $INTERFACE\033[0m" | sudo tee /dev/tty1

echo -e "\033[92mPPPwn:\033[93m C++ $CPPBIN \033[0m" | sudo tee /dev/tty1

echo -e "\033[92mRun:\033[93m $PPPwnPS4 \033[0m" | sudo tee /dev/tty1

if [ $DETECTLINK = true ] ; then
if [[ $DETECTMODE == "2" ]] || [[ $DETECTMODE == "4" ]] ;then
if [[ $(sudo cat /sys/class/net/$INTERFACE/operstate) == *"down"* ]] ; then
while [ true ]
do
if [[ $(sudo cat /sys/class/net/$INTERFACE/operstate) == *"up"* ]] ; then
break
else
coproc read -t 2 && wait "$!" || true
fi
done
fi
fi
fi

echo -e "\033[95mReady for console connection\033[0m" | sudo tee /dev/tty1

if [ $PPDBG = true ] ; then
if [[ $DETECTMODE == "2" ]] ; then
DETECTDES="PS4 Power on detection"
elif [[ $DETECTMODE == "3" ]] ; then
DETECTDES="GoldHEN detection"
elif [[ $DETECTMODE == "4" ]] ; then
DETECTDES="PS4 Power on & GoldHEN detection"
else
DETECTDES="Disable detection"
fi
sudo echo '
CPPMETHOD="'$CPPMETHOD'" >>> '$CPPNAME'
INTERFACE="'$INTERFACE'"
FIRMWAREVERSION="'$FIRMWAREVERSION'"
USBETHERNET='$USBETHERNET'
STAGE2METHOD="'$STAGE2METHOD'" >>> XFGH='$XFGH'
SOURCEIPV6="'$SOURCEIPV6'" >>> XFIP='$XFIP'
DETECTMODE="'$DETECTMODE'" >>> '$DETECTDES'
PPPOECONN='$PPPOECONN'
PWNAUTORUN='$PWNAUTORUN'
TIMEOUT="'$TIMEOUT'"
PPDBG='$PPDBG'
XFWAP="'$XFWAP'"
XFGD="'$XFGD'"
XFBS="'$XFBS'"
XFNWB='$XFNWB' >>> no-wait-padi (XFNW)='$XFNW'
SPRAY_NUM=0x'$SPRAY_NUM'
CORRUPT_NUM=0x'$CORRUPT_NUM'
PIN_NUM=0x'$PIN_NUM'
====================
args from script >>> '$PPPwnPS4'
====================' > /boot/firmware/PPPwn/args.log
fi

pwncounter=0
while [ true ]
do
while read -r stdo ; 
do
if [ $PPDBG = true ] ; then
	echo -e $stdo | sudo tee /dev/tty1 | sudo tee /dev/pts/* | sudo tee -a /boot/firmware/PPPwn/pwn.log
	if [[ $stdo == *"args:"* ]] && [[ $((pwncounter)) -eq 0 ]]; then
		sudo echo -e 'args from c++ binary >>> '$stdo'' >> /boot/firmware/PPPwn/args.log
	fi
	if [[ $stdo == *"NUM"* ]] && [[ $((pwncounter)) -eq 1 ]]; then
		sudo echo -e ''$stdo'' >> /boot/firmware/PPPwn/args.log
	fi
fi
if [[ $stdo == "[+] Done!" ]] ; then
	sudo ip link set $INTERFACE down
	((pwncounter++))
	echo ''$STAGE2METHOD'' | sudo tee /tmp/ps4alreadypwned.txt
	echo -e "\033[32m\nConsole PPPwned! \033[0m\n" | sudo tee /dev/tty1
	if [ $PPPOECONN = true ] ; then
		coproc read -t 5 && wait "$!" || true
		if [ $PPDBG = true ] ; then
			sudo echo -e '\n[+] Done!' >> /boot/firmware/PPPwn/args.log
		fi
		start_pppoe
	else
		if [[ $DETECTMODE == "3" ]] || [[ $DETECTMODE == "4" ]] ;then
		if [[ ${STAGE2METHOD,,} == "goldhen" ]] || [[ ${STAGE2METHOD,,} == *"gold"* ]] ;then
			coproc read -t 5 && wait "$!" || true
			start_pppoe
			coproc read -t 7 && wait "$!" || true
		fi
		fi
		shutdown_device
	fi
	exit 0
fi
if [ $PPDBG = true ] ; then
if [[ $stdo == *"] args:"* ]] ; then
	((pwncounter++))
elif [[ $stdo == *"Scanning for corrupted object...failed"* ]] ; then
 	echo -e "\033[31m\nFailed retrying...\033[0m\n" | sudo tee /dev/tty1
elif [[ $stdo == *"Scanning for corrupted object...found"* ]] ; then
	sudo echo -e '\nTotal PPPwn attempted : '$pwncounter' time(s)\n' >> /boot/firmware/PPPwn/args.log
	sudo echo -e ''$stdo'' >> /boot/firmware/PPPwn/args.log
elif [[ $stdo == *"Unsupported firmware version"* ]] ; then
 	echo -e "\033[31m\nUnsupported firmware version\033[0m\n" | sudo tee /dev/tty1
 	exit 1
elif [[ $stdo == *"Cannot find interface with name of"* ]] ; then
 	echo -e "\033[31m\nInterface $INTERFACE not found\033[0m\n" | sudo tee /dev/tty1
 	exit 1
fi
fi
done < <(timeout $TIMEOUT sudo /boot/firmware/PPPwn/$PPPwnPS4)
sudo ip link set $INTERFACE down
coproc read -t 2 && wait "$!" || true
sudo ip link set $INTERFACE up
coproc read -t 1 && wait "$!" || true
done
