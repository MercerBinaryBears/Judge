class SolutionJudger(object):

	def __init__(self, filename=None, languageJudgeClass=None):
		# Make sure that a file is actual provided to the class
		if filename == None:
			raise Exception('No Filename Provided to judge')
		self.filename = filename

		# if no language judge is provided, attempt to autoload it
		if languageJudgeClass == None:
			languageJudgeClass = self._autoloadLanguageJudge(filename)

		#instantiate the class
		self.judger = languageJudgeClass(filename)

	def _autoloadLanguageJudge(self, filename):
		'''
		Returns an Python Class Object
		'''
		# build the class name from the extension
		extension = filename.split('.')[-1]
		languageJudgeClassName = extension.capitalize() + 'Judge'

		# now, attempt to auto import it
		# (via http://stackoverflow.com/questions/4821104)
		module = __import__('libs.languages.' + languageJudgeClassName)
		return getattr(module, languageJudgeClassName)