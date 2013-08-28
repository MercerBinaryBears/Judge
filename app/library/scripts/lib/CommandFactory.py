import importlib

class CommandFactory:
	@staticmethod
	def build(argv):
		'''Returns the class that processes the provided judge command'''

		# if there was no argument, we cannot build a command, so just return
		if len(argv) < 2:
			return None

		# build the actual class name
		command_class = argv[1].strip().title() + 'Command'

		import_string = 'lib.judge_commands.{0}'.format(command_class)

		# attempt to import the model
		try:
			module = importlib.import_module(import_string)
			class_obj = getattr(module, command_class)
			return class_obj()
		except ImportError as ie:
			print 'Attempted to import {3}, Could not find class {0}. Command {1} is invalid: {2}'.format(command_class, argv[1], ie, import_string)
			return None