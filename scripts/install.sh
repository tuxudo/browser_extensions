#!/bin/bash

# browser_extensions controller
CTL="${BASEURL}index.php?/module/browser_extensions/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/browser_extensions.py" -o "${MUNKIPATH}preflight.d/browser_extensions.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/browser_extensions.py"

	# Set preference to include this file in the preflight check
	setreportpref "browser_extensions" "${CACHEPATH}browser_extensions.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/browser_extensions.py"

	# Signal that we had an error
	ERR=1
fi