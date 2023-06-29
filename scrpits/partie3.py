import json
import joblib
import sys
from sys import argv
import sklearn
import argparse

best_model_svm = joblib.load('best_model_svm.pkl')
best_model_rf = joblib.load('best_model_rf.pkl')
best_model_mlp = joblib.load('best_model_mlp.pkl')

# Fonction de prédiction de gravité d'accident

#print(accident_info)
classification_method = argv[2]
print(argv[1])
print(argv[2])

def predict_accident_gravity(accident_info, classification_method):
    accident_info = [float(arg) for arg in argv[1].split(',')]
    accident_info = [accident_info]
    # Utiliser la méthode de classification spécifiée
    if classification_method == "SVM":
        prediction = best_model_svm.predict(accident_info)
    elif classification_method == "RF":
        prediction = best_model_rf.predict(accident_info)
    elif classification_method == "MLP":
        prediction = best_model_mlp.predict(accident_info)
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