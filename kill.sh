#!/bin/sh

PID=$(lsof -t -i:8080)

if [ -n "$PID" ]; then
    kill -9 $PID
fi