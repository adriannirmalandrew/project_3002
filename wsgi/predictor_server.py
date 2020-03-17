import flask
from flask import Flask, request, render_template, Response
import predictor
import hospitals

predictorApp=Flask(__name__)

@predictorApp.route('/diagnose', methods=['POST'])
def predict():
	#Symptoms are set as a CSV list:
	symptoms=request.form['symptoms'].split(',')
	symptoms=symptoms[:-1] #Remove empty entry at end
	#Age:
	age=float(request.form['age'])
	#Make prediction:
	diagnosed_disease=predictor.predict(symptoms, age)
	#Return results:
	#return "The diagnosed disease is: " + predictor.predict(symptoms, age);
	return render_template("diagnosis.html", disease_name=diagnosed_disease, symptoms=symptoms, age=age)

@predictorApp.route('/tophospitals', methods=['POST'])
def getHospitals():
	#Get latitude and longitude:
	latitude=request.form['latitude']
	longitude=request.form['longitude']
	#Get hospitals:
	hospitalList=hospitals.bestHospitals(latitude, longitude)
	#Create CSV list:
	hospitalListCsv=str()
	for hospital in hospitalList:
		hospitalListCsv=hospitalListCsv + hospital + ','
	#Return comma-delimited response:
	return Response(hospitalListCsv, mimetype='text/csv')

if __name__=='__main__':
	predictorApp.run()
