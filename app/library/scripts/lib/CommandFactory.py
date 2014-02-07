from Autoloader import Autoloader

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
		return Autoloader.importClass(import_string, command_class)