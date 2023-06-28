import sys
import pandas as pd
from sklearn.cluster import KMeans
import json
import numpy as np

latitude = float(sys.argv[1])
longitude = float(sys.argv[2])

data = pd.read_csv(sys.argv[3], sep=',')


tab = []
for i in range(0, len(data)):
    # récupère les latitudes et longitudes de chaque ligne et les stocke dans un tableau
    tab.append([data['latitude'][i], data['longitude'][i]])

KM = KMeans(n_clusters=len(data), random_state=0, n_init='auto').fit(tab)

prediction = KM.predict([[latitude, longitude]])
lat_centroid = KM.cluster_centers_[prediction[0]][0]
long_centroid = KM.cluster_centers_[prediction[0]][1]

# Création du dictionnaire JSON
result = {
    'cluster':{
        "latitude du centroid": lat_centroid,
        "longitude du centroid": long_centroid
    },
    'accident':{
        'latitude de l\'accident': latitude,
        'longitude de l\'accident': longitude
    },
}

# Conversion du dictionnaire en JSON
json_output = json.dumps(result)
print(json_output)