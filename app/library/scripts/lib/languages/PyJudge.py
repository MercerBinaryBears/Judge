from LanguageJudge import LanguageJudge

class PyJudge(LanguageJudge):
	def getCompileString(self):
		return 'echo \'\''

	def checkCompilePassed(self, output):
		return True

	def getRunCommand(self):
		return "python {0}".format(self.filename)

	def cleanup(self):
		return None;