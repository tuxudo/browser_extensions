#!/usr/local/munkireport/munkireport-python3

import os
import subprocess
import sys
import re
import glob
import json

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist

def get_users():

    # Get all users' home folders
    cmd = ['dscl', '.', '-readall', '/Users', 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    users = []

    for user in output.decode().split('\n'):
        if 'NFSHomeDirectory' in user and '/var/empty' not in user:
            userpath = user.replace("NFSHomeDirectory: ", "")
            users.append(user.replace("NFSHomeDirectory: ", ""))

    return users

def process_chrome(chrome_extension, user, browser):

    extension_manifest = json.loads(open(chrome_extension, 'r').read().strip())
    
    extension_info = {}

    for item in extension_manifest:
        if item == "version":
            extension_info['version'] = extension_manifest[item]

        elif item == "description" and extension_manifest[item].startswith('__MSG'):
            try:
                locale_file = open(chrome_extension.replace("manifest.json", "_locales/"+extension_manifest['default_locale']+"/messages.json"), 'r')
                extension_localization = json.loads(locale_file.read().strip())
                extension_localization_lower = {k.lower():v for k,v in list(extension_localization.items())}
                local_name = extension_manifest['description'].replace("__MSG_","").replace("__","").lower()
                extension_info['description'] = extension_localization_lower[local_name]["message"]
            except:
                extension_info['description'] = ""
        elif item == "description":
            extension_info['description'] = extension_manifest[item]

        elif item == "name" and extension_manifest[item].startswith('__MSG'):
            try:
                locale_file = open(chrome_extension.replace("manifest.json", "_locales/"+extension_manifest['default_locale']+"/messages.json"), 'r')
                extension_localization = json.loads(locale_file.read().strip())
                extension_localization_lower = {k.lower():v for k,v in list(extension_localization.items())}
                local_name = extension_manifest['name'].replace("__MSG_","").replace("__","").lower()
                extension_info['name'] = extension_localization_lower[local_name]["message"]
            except:
                extension_info['description'] = ""
        elif item == "name":
            extension_info['name'] = extension_manifest[item]

    path_dict = chrome_extension.split('/')
    extension_info['extension_id'] = path_dict[-3:][0]
    extension_info['user'] = user
    extension_info['date_installed'] = str(int(os.path.getmtime(chrome_extension)))
    extension_info['browser'] = browser
    return extension_info

def process_firefox(firefox_extension, user):

    extension_info = {}

    for item in firefox_extension:
        if item == "version":
            extension_info['version'] = firefox_extension[item]

        elif item == "active":
            extension_info['enabled'] = firefox_extension[item]
            
        elif item == "installDate" or item == "updateDate":
            extension_info['date_installed'] = str(firefox_extension[item]/1000)
            
        elif item == "id":
            extension_info['extension_id'] = firefox_extension[item]
            
        elif item == "defaultLocale":
            
            for locale_item in firefox_extension[item]:
                if locale_item == "description" and firefox_extension[item][locale_item]:
                    extension_info['description'] = firefox_extension[item][locale_item]
                elif locale_item == "name" and firefox_extension[item][locale_item]:
                    extension_info['name'] = firefox_extension[item][locale_item]
                elif locale_item == "creator" and firefox_extension[item][locale_item]:
                    extension_info['developer'] = firefox_extension[item][locale_item]

    extension_info['user'] = user
    extension_info['browser'] = "Firefox"

    return extension_info

def process_browsers(users):

    if users == "":
        return []

    out = []
    for user in users:

        # Check for Chrome extensions
        chrome_extension_path = user+"/Library/Application Support/Google/Chrome/Default/Extensions/"
        if os.path.isdir(chrome_extension_path):
            for chrome_extension in glob.glob(chrome_extension_path+'*/*/manifest.json'):
                out.append(process_chrome(chrome_extension, user.replace("/Users/",""), "Google Chrome"))

        # Check for Edge extensions
        edge_extension_path = user+"/Library/Application Support/Microsoft Edge/Default/Extensions/"
        if os.path.isdir(edge_extension_path):
            for edge_extension in glob.glob(edge_extension_path+'*/*/manifest.json'):
                out.append(process_chrome(edge_extension, user.replace("/Users/",""), "Microsoft Edge"))

        # Check for Firefox extensions
        firefox_path = user+"/Library/Application Support/Firefox/Profiles/"
        if os.path.isdir(firefox_path):
            for firefox_extension_json_path in glob.glob(firefox_path+'*/extensions.json'):
                firefox_extension_json = json.loads(open(firefox_extension_json_path, 'r').read().strip())
                for firefox_extension in firefox_extension_json['addons']:                        
                    out.append(process_firefox(firefox_extension, user.replace("/Users/","")))

    return out

def main():
    """Main"""

    # Get information about the browser extensions
    users = get_users()
    result = process_browsers(users)

    # Write browser extensions to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'browser_extensions.plist')
    FoundationPlist.writePlist(result, output_plist)
    #print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()
