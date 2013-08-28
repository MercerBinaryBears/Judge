import json
import os

class ConfigFile(object):
	def __init__(self, filename="config.json"):
		'''Initializes a config file with the passed filename'''
		self.filename = filename

	def __enter__(self):
		'''Reads the config file in'''

		# create the file if it doesn't exist
		if not os.path.exists(self.filename):
			empty_file = open(self.filename, 'w')
			empty_file.write("{}")
			empty_file.close()

		# read the configs in and then close the file
		config_file_handle = open(self.filename, 'r')
		self.configs = json.loads( config_file_handle.read() )
		config_file_handle.close()

		return self

	def __exit__(self, type, value, traceback):
		'''Writes the config file'''
		config_file_handle = open(self.filename, 'w+')
		config_file_handle.write(json.dumps(self.configs))
		config_file_handle.close()

	def get(self, key):
		if key not in self.configs:
			return None
		return self.configs[key]

	def set(self, key, value):
		self.configs[key] = value