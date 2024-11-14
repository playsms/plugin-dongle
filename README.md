# plugin-dongle

playSMS gateway plugin for Asterisk chan_dongle.

Info          | Value
------------- | ---------------------------------
Author        | Anton Raharja `<araharja@pm.me>`
Update        | 241114
Version       | 1.0
Compatible    | playSMS 1.4.7
playSMS       | https://playsms.org

# License

[MIT License](LICENSE)

# Installation

You will need an installed and working playSMS, preferred playSMS version 1.4.7 (current latest).

You also need to have a fully working Asterisk with chan_dongle loaded and configured properly.

Assumed your playSMS is installed with these setups:

- Your playSMS web files is in `/home/user/web/playsms`
- Your playSMS database is `playsms`

Also, assumed your Asterisk binary installed in `/usr/sbin/asterisk`

Note, if your Asterisk binary not in above location then you must change `_ASTERISK_` constant after installation in `plugin/gateway/dongle/config.php`.

Follow below steps in order:

1. Clone this repo to your playSMS server

   ```
   cd ~
   git clone https://github.com/playsms/plugin-dongle.git
   cd plugin-dongle
   ```

2. Copy gateway source to playSMS `plugin/gateway/`

   ```
   cp -rR src/dongle /home/user/web/playsms/plugin/gateway/
   ```

3. Restart `playsmsd`

   ```
   playsmsd restart
   playsmsd check
   ```

# Usage

Assumed your playSMS installed in `/home/user/web/playsms`

Asterisk dialplan for incoming SMS:

```
[dongle-incoming]
exten => sms,1,NoOp(Incoming SMS handler starts)
exten => sms,n,Set(PLAYSMS=/home/user/web/playsms)
exten => sms,n,Set(PLAYSMSIN=/usr/bin/php -q ${PLAYSMS}/plugin/gateway/dongle/callback.php)
exten => sms,n,GotoIf($[ "x${PLAYSMS}" = "x" ]?end)
exten => sms,n,GotoIf($[ "x${PLAYSMSIN}" = "x" ]?end)
exten => sms,n,Verbose(Incoming SMS smsc:${DONGLENAME} from:${CALLERID(num)} msg:${BASE64_DECODE(${SMS_BASE64})})
exten => sms,n,System(${PLAYSMSIN} "${PLAYSMS}" "${DONGLENAME}" "${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}" "${CALLERID(num)}" "${SMS_BASE64}")
exten => sms,n(end),Hangup()
```
