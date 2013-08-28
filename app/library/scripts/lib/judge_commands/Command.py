from abc import abstractmethod

class Command(object):
	@abstractmethod
	def execute(self, arguments):
		pass