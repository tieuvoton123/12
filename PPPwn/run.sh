#!/bin/bash

if [ -f /boot/firmware/PPPwn/config.sh ]; then
source /boot/firmware/PPPwn/config.sh
fi
if [ -z $CPPMETHOD ]; then CPPMETHOD="3"; fi
if [ -z $INTERFACE ]; then INTERFACE="eth0"; fi
if [ -z $FIRMWAREVERSION ]; then FIRMWAREVERSION="11.00"; fi
if [ -z $USBETHERNET ]; then USBETHERNET=false; fi
if [ -z $STAGE2METHOD ]; then STAGE2METHOD="goldhen"; fi
if [ -z $SOURCEIPV6 ]; then SOURCEIPV6="2"; fi
if [ -z $CUSTOMIPV6 ]; then CUSTOMIPV6="9f9f:41ff:9f9f:41ff"; fi
if [ -z $DETECTMODE ]; then DETECTMODE="2"; fi

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
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --stage1 "$STAGE1F" --stage2 "$STAGE2F""
else
if [ -f /boot/firmware/PPPwn/pconfig.sh ]; then
source /boot/firmware/PPPwn/pconfig.sh
fi
if [ -z $XFWAP ]; then XFWAP="1"; fi
if [ -z $XFGD ]; then XFGD="4"; fi
if [ -z $XFBS ]; then XFBS="0"; fi
if [ -z $XFNWB ]; then XFNWB=true; fi
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
PPPwnPS4="$CPPBIN --interface "$INTERFACE" --fw "${FIRMWAREVERSION//.}" --ipv "$XFIP" --wait-after-pin $XFWAP --groom-delay $XFGD --buffer-size $XFBS $XFNW $XFGH"
elif [[ $CPPMETHOD == *"4"* ]] || [[ ${CPPMETHOD,,} == *"n"* ]] ;then
CPPBIN+='nn9dev'
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

echo -e "\033[95mReady for console connection\033[0m" | sudo tee /dev/tty1

pwncounter=0
while [ true ]
do
sudo /boot/firmware/PPPwn/$PPPwnPS4
if [ $? -eq 0 ]; then
sudo ip link set $INTERFACE down
coproc read -t 5 && wait "$!" || true
sudo ip link set $INTERFACE down
coproc read -t 2 && wait "$!" || true
sudo poweroff
coproc read -t 2 && wait "$!" || true
sudo shutdown -P now
else
sudo ip link set $INTERFACE down
coproc read -t 2 && wait "$!" || true
sudo ip link set $INTERFACE up
coproc read -t 1 && wait "$!" || true
if [[ $((pwncounter++)) -eq 10 ]]; then
sudo systemctl restart pipwn
fi
fi
done
