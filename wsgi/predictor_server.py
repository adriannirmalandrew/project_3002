#!/usr/bin/python

import flask
from flask import Flask, request, render_template, Response
import predictor
import hospitals
import datetime
import MySQLdb

predictorApp=Flask(__name__)

@predictorApp.route('/diagnose', methods=['POST'])
def predict():
	#Symptoms are set as a CSV list:
	symptoms_csv = request.form['symptoms']
	symptoms = symptoms_csv.split(',')
	symptoms = symptoms[:-1] #Remove empty entry at end
	#Age:
	age = int(request.form['age'])
	#Make prediction:
	diagnosed_disease = predictor.predict(symptoms, age)
	
	#Connect to database:
	dbConn = MySQLdb.connect('localhost', 'project', 'project', 'cse3002')
	dbCursor = dbConn.cursor()
	
	#Get recommended drugs for disease:
	dbCursor.execute('select drugs_list from drugs where disease_name=%s', (diagnosed_disease,))
	drugs_string = dbCursor.fetchone()[0]
	#Parse list:
	drugs_string=drugs_string.split(':')
	final_dstring=""
	for i in range(len(drugs_string)-1):
		d = drugs_string[i]
		drug = d.split(',')
		final_dstring = final_dstring + drug[0]
		if drug[1]:
			final_dstring = final_dstring + ' (Prescription Required)'
		final_dstring = final_dstring + ', '
	if final_dstring == '':
		final_dstring='None'
	
	#Get session ID:
	session_id = request.cookies.get('session_id')
	#Check if user is anonymous:
	if session_id != 'ANONYMOUS':
		#Get current date and time:
		diag_datetime = str(datetime.datetime.now()).split('.')[0]
		#Get username:
		dbCursor.execute('select username from login where session_id=%s', (session_id,))
		username = dbCursor.fetchone()[0]
		#Commit record to database:
		dbCursor.execute('insert into diagnoses values(%s, %s, %s, %s)', (username, diag_datetime, diagnosed_disease, symptoms_csv[:-1]))
		dbConn.commit()
	
	#Close DB connection:
	dbConn.close()
	#Return results:
	return render_template("diagnosis.html", disease_name=diagnosed_disease, symptoms=symptoms, age=age, drugs=final_dstring)

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
