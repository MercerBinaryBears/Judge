import os
from LanguageJudge import LanguageJudge

class JavaJudge(LanguageJudge):
	def getCompileString(self):
		return "javac {0}".format(self.filename)

	def checkCompilePassed(self, output):
		return output == ''

	def getRunCommand(self):
		return "java {0}".format(self.filename.split('.')[0])

	def cleanup(self):
		os.remove("{0}.class".format(self.filename.split('.')[0]));