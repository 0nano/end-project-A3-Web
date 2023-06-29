import json
import pandas as pd
import joblib
import sys
from sys import argv
import sklearn
import argparse

best_model_svm = joblib.load('../scrpits/best_model_svm.pkl')
best_model_rf = joblib.load('../scrpits/best_model_rf.pkl')
best_model_mlp = joblib.load('../scrpits/best_model_mlp.pkl')

# Fonction de prédiction de gravité d'accident

#print(accident_info)
classification_method = argv[2]


def predict_accident_gravity(accident_info, classification_method):
    
    temp = argv[1].split(',')
    descr_athmo = float(temp[0])
    descr_lum = float(temp[1])
    descr_etat_surf = float(temp[2])
    age = float(temp[3])
    descr_dispo_secu = float(temp[4])
    
    df = pd.DataFrame([[descr_athmo, descr_lum, descr_etat_surf, age, descr_dispo_secu]], columns=['descr_athmo', 'descr_lum', 'descr_etat_surf', 'age', 'descr_dispo_secu'])
    
    # Utiliser la méthode de classification spécifiée
    if classification_method == "SVM":
        prediction = best_model_svm.predict(df)
    elif classification_method == "RF":
        prediction = best_model_rf.predict(df)
    elif classification_method == "MLP":
        prediction = best_model_mlp.predict(df)
    else:
        #Méthode de classification non prise en charge
        return None
    predict_int = int(prediction[0])
    # Créer un dictionnaire contenant la prédiction de gravité
    result = {"gravitee": predict_int}
    # Conversion du dictionnaire en JSON
    json_output = json.dumps(result)
    print(json_output)


predict_accident_gravity(sys.argv[1],sys.argv[2])