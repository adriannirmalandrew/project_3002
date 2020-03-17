
# 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=13.0827,80.2707&type=hospital&key=AIzaSyDKIGvxnLMbApr2Ocl6_3XbP5YARDDzEdw&radius=1000'

# 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=13.0827,80.2707&type=hospital&key=AIzaSyDKIGvxnLMbApr2Ocl6_3XbP5YARDDzEdw&rankby=distance'

import requests
from haversine import haversine, Unit
import time
#API_KEY = 'AIzaSyDKIGvxnLMbApr2Ocl6_3XbP5YARDDzEdw'
API_KEY = 'AIzaSyDSqb3ufNkvYUmrAw4mtJ0dieQXwZSS1TA'
radius = 2000
def bestHospitals(x, y):
    placesUrl = f'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location={x},{y}&type=hospital&key={API_KEY}'
    url = placesUrl + f'&radius={radius}'
    scoreToHospital = {}
    scores = []
    results = requests.get(url)
    hospitals = results.json()['results']
    for h in hospitals:
        try:
            name = h['name']
            rating=h['rating']
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

    scores = sorted(scores, reverse=True)
    
    print(scores)
    
    topN = 3
    for i in range(topN):
        print(scoreToHospital[scores[i]]['name'])

    top3 = [scoreToHospital[scores[i]] for i in range(topN) ]
    return top3
#bestHospital(13.0827,80.2707)
