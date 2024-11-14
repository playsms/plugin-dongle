# plugin-dongle

playSMS gateway plugin for Asterisk chan_dongle.

Info          | Value
------------- | ---------------------------------
Author        | Anton Raharja `<araharja@pm.me>`
Update        | 241114
Version       | 1.0
Compatible    | playSMS 1.4.7
playSMS       | https://playsms.org

# Installation

You will need an installed and working playSMS, preferred playSMS version 1.4.7 (current latest).

Assumed your playSMS is installed with these setups:

- Your playSMS web files is in `/home/user/web/playsms`
- Your playSMS database is `playsms`

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

# License

[MIT](LICENSE)
