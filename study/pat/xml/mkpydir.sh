#!/bin/bash

mkdir -p py
echo "from pkgutil import extend_path" > py/__init__.py
echo "__path__ = extend_path(__path__, __name__)" >> py/__init__.py

