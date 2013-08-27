import importlib

class CommandFactory:
	@staticmethod
	def build(argv):
		'''Returns the class that processes the provided judge command'''

		# build the actual class name
		command_class = argv[1].strip().title() + 'Command'

		# attempt to import the model
		try:
			module = importlib.import_module('lib.commands.{0}'.format(command_class))
			class_obj = getattr(module, command_class)
			return class_obj()
		except ImportError as ie:
			print 'Could not find class {0}. Command {1} is invalid: {2}'.format(command_class, argv[1], ie)
			return None