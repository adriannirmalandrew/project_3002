#!/usr/bin/python

import requests
from haversine import haversine, Unit
import time

API_KEY = 'REDACTED'
radius = 2000

def bestHospitals(x, y):
	placesUrl = f'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location={x},{y}&type=hospital&key={API_KEY}'
	url = placesUrl + f'&radius={radius}'
	scoreToHospital = {}
	scores = []
	results = requests.get(url)
	results_hospitals = results.json()['results']
	
	hospitals = list()
	#Get location and vicinity from results:
	for h in results_hospitals:
		h_lat = h['geometry']['location']['lat']
		h_lng = h['geometry']['location']['lng']
		hospitals.append({'name': h['name'], 'near': h['vicinity'], 'lat': h_lat, 'lng': h_lng, 'distance': 0})
	
	#Find distance from current location to hospitals:
	for h in hospitals:
		#Calculate distance:
		hospDistance = haversine((x, y), (h['lat'], h['lng']))
		#Set in hospital list:
		h['distance'] = hospDistance
	
	#Sort hospitals by distance:
	hospitals = sorted(hospitals, key = lambda h:h['distance'])
	#Convert to strings:
	topHospitals = list()
	for i in range(0, 3):
		topHospitals.append(hospitals[i]['name'] + ' (' + hospitals[i]['near'] + ')')
	
	return topHospitals

'''
for h in hospitals:
	try:
		name = h['name']
		rating = h['rating']
		userRatingsTotal = h['user_ratings_total']   
	except KeyError:
		continue
	hospitalLat = h['geometry']['location']['lat']
	hospitalLng = h['geometry']['location']['lng']
	hospitalLoc = (hospitalLat, hospitalLng)
	
	patientDistance = haversine((x,y), hospitalLoc)
	score = (rating * userRatingsTotal) / patientDistance
	
	#print(name, '\t', rating, '\t', userRatingsTotal, '\t', score, '\t', patientDistance)
	
	scoreToHospital[score] = h
	scores.append(score)

#scores = sorted(scores, reverse=True)
#print(scores)
'''

'''
topN = 3
top3 = []
for i in range(topN):
	print(scoreToHospital[scores[i]]['name'])
	top3.append(scoreToHospital[scores[i]]['name'])

#top3 = [scoreToHospital[scores[i]] for i in range(topN) ]
return top3
'''
