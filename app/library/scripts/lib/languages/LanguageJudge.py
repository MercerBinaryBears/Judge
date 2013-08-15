from abc import abstractmethod

class LanguageJudge(object):
	'''Base class for inheritance for language compilers/judges

	Proscribes that a LanguageJudge should know how to compile a file,
	check that it's compile passed, and run the file.
	'''

	def __init__(self, filename=''):
		self.filename = filename

	@abstractmethod
	def getCompileString(self):
		'''Builds the shell command that compiles code in this LanguageJudge\'s language'''
		return ''

	@abstractmethod
	def checkCompilePassed(self, compileOutput=''):
		'''Checks the compile output of a compilation in this LanguageJudge\'s language for successful output'''
		return False

	@abstractmethod
	def getRunCommand(self):
		'''Build the shell command that runs a program in this language (reading from stdin, printing to stdout)'''
		return ''

	@abstractmethod
	def cleanup(self):
		'''Deletes any temporary files created in the process of judging'''
		return None