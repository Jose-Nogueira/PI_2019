# The log is written to here - please make sure your user has write permissions.
LOG=/var/log/node-red.log
LOG_m=/var/log/mosquito.log

echo "Node-RED service start: "$(date) >> $LOG
node /usr/lib/node_modules/node-red/red /node_flows/flow.json >> $LOG &

echo "Mosquitto service start: "$(date) >> $LOG_m
mosquitto -c /mosquitto/mosquitto.conf >> $LOG_m &

exit 0
