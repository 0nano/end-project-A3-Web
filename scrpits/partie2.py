# Importation des librairies
import numpy as np
import pandas as pd

# Importation des données d'apprentissage
dataframe = pd.read_csv("export_IA.csv", sep=',', engine='c', encoding='utf-8', low_memory=False)

import platform

if platform.system() == 'Windows':
    # préparation des données, conversions des dates et des heures en entier sans les séparateurs
    dataframe['date'] = pd.to_datetime(dataframe['date'], format='%Y-%m-%d %H:%M:%S').dt.strftime('%Y%m%d%H%M%S').astype('Int64')
else :
    # préparation des données, conversions des dates et des heures en entier sans les séparateurs
    dataframe['date'] = pd.to_datetime(dataframe['date'], format='%Y-%m-%d %H:%M:%S').dt.strftime('%Y%m%d%H%M%S').astype(int)

# Transformartion des codes_insee en entier, en remplacant les A et B par 0
dataframe['id_code_insee'] = dataframe['id_code_insee'].str.replace('A', '0')
dataframe['id_code_insee'] = dataframe['id_code_insee'].str.replace('B', '0')

# On supprime la colonne 'num_veh' et 'ville' car elles ne nous sont pas utiles
dataframe = dataframe.drop('num_veh', axis=1)
dataframe = dataframe.drop('ville', axis=1)

# On tranforme la colonne 'descr_type_col' en entier
echelle = dataframe['descr_type_col'].unique()
dataframe['descr_type_col'] = dataframe['descr_type_col'].replace(echelle, [0,1,2,3,4,5,6]) # type: ignore

# On transforme la colonne 'descr_dispo_secu' en entier
echelle = dataframe['descr_dispo_secu'].unique()
dataframe['descr_dispo_secu'] = dataframe['descr_dispo_secu'].replace(echelle, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]) # type: ignore

# On supprime la colonne 'department_name' car elle ne nous est pas utile
dataframe = dataframe.drop('department_name', axis=1)

# On ajoute une colonne 'region_number' qui contient le numéro de la région selon le region_name
REGIONS = {
    'auvergne-rhône-alpes': '84',
    'bourgogne-franche-comté': '27',
    'bretagne': '53',
    'centre-val de loire': '24',
    'corse': '94',
    'grand est': '44',
    'guadeloupe': '01',
    'guyane': '03',
    'hauts-de-france': '32',
    'île-de-france': '11',
    'la réunion': '04',
    'martinique': '02',
    'normandie': '28',
    'nouvelle-aquitaine': '75',
    'occitanie': '76',
    'pays de la loire': '52',
    'provence-alpes-côte d\'azur': '93'
}

# On ajoute une colonne 'region_number' qui contient le numéro de la région selon le region_name et REGIONS
# On met en minuscule les clés de régions pour éviter les erreurs
dataframe['region_number'] = dataframe['region_name'].replace(REGIONS)
dataframe = dataframe.drop('region_name', axis=1)

# On remplace le A et le B par un 0 dans le departement_number
dataframe['department_number'] = dataframe['department_number'].str.replace('A', '0')
dataframe['department_number'] = dataframe['department_number'].str.replace('B', '0')

# On défini les valeurs de la colonne 'id_code_insee', 'department_number' et 'region_number' en entier
dataframe['id_code_insee'] = dataframe['id_code_insee'].astype(int)
dataframe['department_number'] = dataframe['department_number'].astype(int)
dataframe['region_number'] = dataframe['region_number'].astype(int)

# On supprime les colonnes 'an_nais', 'id_usa', 'num_acc' et 'department_number' car elles sont corolés à d'autres variables
dataframe = dataframe.drop(columns=['an_nais', 'id_usa', 'Num_Acc', 'department_number'])

df = dataframe[['descr_athmo', 'descr_lum', 'descr_etat_surf',
       'age', 'descr_dispo_secu']]

from sklearn.neighbors import KNeighborsClassifier

# Définition de KNN
knn = KNeighborsClassifier(n_neighbors=7)

knn.fit(df, dataframe['descr_grav'])

# Récupération des données à prédire
import sys

descr_athmo = int(sys.argv[1])
descr_lum = int(sys.argv[2])
descr_etat_surf = int(sys.argv[3])
age = float(sys.argv[4])
descr_dispo_secu = int(sys.argv[5])
print("tt")

# On réalise un dataframe avec les données récupérées
X_test = pd.DataFrame([[descr_athmo, descr_lum, descr_etat_surf, age, descr_dispo_secu]])
result = knn.predict(X_test)
print("tt")

# Exportation des données prédites
import json

export_json = {
    'gravite': int(result[0])
}

print(json.dumps(export_json))