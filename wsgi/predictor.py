import argparse
import math
import logging
import numpy as np
import pandas as pd

#List of symptoms and diseases
symptoms=[]
disease=[]

#Read training data file:
df=pd.read_csv("TrainingProj.csv")
for col in df.columns:
    symptoms.append(col)
n=len(symptoms)
symptoms=symptoms[:-1]
#Diseases names are in the last column:
disease=list(df[df.columns[n-1]])
#Initialize all symptom fields as "false":
l=[]
for x in range(0,len(symptoms)):
    l.append(0)

#Assign a numerical ID to each disease:
df.replace({'diseases':{'Fungal infection':0,'Allergy':1,'GERD':2,'Chronic cholestasis':3,'Drug Reaction':4,'Peptic ulcer diseae':5,'AIDS':6,'Diabetes ':7,'Gastroenteritis':8,'Bronchial Asthma':9,'Hypertension ':10,'Migraine':11,'Cervical spondylosis':12,'Paralysis (brain hemorrhage)':13,'Jaundice':14,'Malaria':15,'Chicken pox':16,'Dengue':17,'Typhoid':18,'hepatitis A':19,'Hepatitis B':20,'Hepatitis C':21,'Hepatitis D':22,'Hepatitis E':23,'Alcoholic hepatitis':24,'Tuberculosis':25,'Common Cold':26,'Pneumonia':27,'Dimorphic hemmorhoids(piles)':28,'Heart attack':29,'Varicose veins':30,'Hypothyroidism':31,'Hyperthyroidism':32,'Hypoglycemia':33,'Osteoarthristis':34,'Arthritis':35,'(vertigo) Paroymsal  Positional Vertigo':36,'Acne':37,'Urinary tract infection':38,'Psoriasis':39,
'Impetigo':40}}, inplace=True)

#Get X and Y lists and train the model:
x_train= df[symptoms]
y_train= df[["diseases"]]
k1=np.ravel(y_train)

#Read testing data file:
tr=pd.read_csv("TestingProj.csv")
#Assign a numerical ID to each disease:
tr.replace({'prognosis':{'Fungal infection':0,'Allergy':1,'GERD':2,'Chronic cholestasis':3,'Drug Reaction':4,'Peptic ulcer diseae':5,'AIDS':6,'Diabetes ':7,'Gastroenteritis':8,'Bronchial Asthma':9,'Hypertension ':10,'Migraine':11,'Cervical spondylosis':12,'Paralysis (brain hemorrhage)':13,'Jaundice':14,'Malaria':15,'Chicken pox':16,'Dengue':17,'Typhoid':18,'hepatitis A':19,'Hepatitis B':20,'Hepatitis C':21,'Hepatitis D':22,'Hepatitis E':23,'Alcoholic hepatitis':24,'Tuberculosis':25,'Common Cold':26,'Pneumonia':27,'Dimorphic hemmorhoids(piles)':28,'Heart attack':29,'Varicose veins':30,'Hypothyroidism':31,'Hyperthyroidism':32,'Hypoglycemia':33,'Osteoarthristis':34,'Arthritis':35,'(vertigo) Paroymsal  Positional Vertigo':36,'Acne':37,'Urinary tract infection':38,'Psoriasis':39,'Impetigo':40}}, inplace=True)

#Test the model:
x_test= tr[symptoms]
y_test = tr[["prognosis"]]
k2=np.ravel(y_test)

def predict(syms, age):
    from sklearn.naive_bayes import GaussianNB
    from sklearn.metrics import accuracy_score
    
    #Using a Gaussian Naive Bayes classifier:
    clf = GaussianNB()
    clf = clf.fit(x_train,k1)

    from sklearn.metrics import accuracy_score
    y_pred=clf.predict(x_test)

    #List of symptoms:
    #Set 'true' fields for specified symptoms:
    for k in range(0,len(symptoms)-4):
        for z in syms:
            if(z==symptoms[k]):
                l[k]=1

    #Set corresponding "age" field to "true":
    if(age<10):
        l[k+1]=1
        l[k+2]=0
        l[k+3]=0        
        l[k+4]=0
        k=k+4
    elif(age<20):
        l[k+1]=0
        l[k+2]=1
        l[k+3]=0        
        l[k+4]=0
        k=k+4
    elif(age<40):
        l[k+1]=0
        l[k+2]=0
        l[k+3]=1        
        l[k+4]=0
        k=k+4
    else:
        l[k+1]=0
        l[k+2]=0
        l[k+3]=0        
        l[k+4]=1
        k=k+4
    
    #Get predictions from model:
    test = [l]
    predict = clf.predict(test)
    #Print the disease with the highest probability:
    predicted=predict[0]
    #print(disease[predicted])
    return disease[predicted]

#Main method:
if __name__=="__main__":
    predict()
