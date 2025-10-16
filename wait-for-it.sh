#!/bin/sh
# wait-for-it.sh

TIMEOUT=$1
HOST=$2
PORT=$3

echo "Waiting for $HOST:$PORT to be ready..."

for i in $(seq $TIMEOUT); do
  nc -z $HOST $PORT > /dev/null 2>&1
  RESULT=$?
  if [ $RESULT -eq 0 ]; then
    echo "$HOST:$PORT is ready."
    break
  fi
  sleep 1
done

if [ $RESULT -ne 0 ]; then
  echo "Timeout waiting for $HOST:$PORT"
  exit 1
fi

exec "$@"