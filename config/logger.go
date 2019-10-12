package config

import (
	"os"

	logrus "github.com/sirupsen/logrus"
)

type Logger struct {
	Log *logrus.Logger
}

func (l *Logger) InitLogger() *logrus.Logger {
	l.Log = logrus.New()
	file, err := os.OpenFile("logger.log", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
	if err == nil {
		l.Log.Out = file
	} else {
		l.Log.Info("Failed to log to file, using default stderr")
	}
	return l.Log
}

func (l *Logger) LogError(err error) {
	l.Log.Error(err)
}

func (l *Logger) LogInfo(info interface{}) {
	l.Log.Info(info)
}
