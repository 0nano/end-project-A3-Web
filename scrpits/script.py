# Importation des librairies
import mysql.connector
import os

# Connection a la base de donnees
mydb = mysql.connector.connect(
    host="localhost",
    user='etu724',
    password='unjvjhys',
    database='etu724',
    port='3306')

# Creation du curseur
mycursor = mydb.cursor()

# Récupère les commandes de model.sql et les exécute
with open('../end-project-A3-Web/sql/model.sql', 'r') as file:
    sql_statements = file.read()

# Exécute les commandes SQL une par une
for result in mycursor.execute(sql_statements, multi=True):
    pass

mydb.commit()



#################### DESCR_LUM ####################
# Dictionnaire pour convertir les descriptions en id
dico_lum = {
    "Crépuscule ou aube": 1,
    "Plein jour": 2,
    "Nuit sans éclairage public": 3,
    "Nuit avec éclairage public allumé": 4,
    "Nuit avec éclairage public non allumé": 5
}

with open('../end-project-A3-Web/scrpits/datas.csv', 'r') as file:
    descriptions = set()  # Utilisation d'un set pour stocker les descriptions uniques
    first_line = True  # Variable pour suivre la première ligne

    for line in file:
        if first_line:
            first_line = False
            continue  # Passe à l'itération suivante pour ignorer la première ligne

        line = line.split(',')
        description_id = int(line[11].strip())  # Récupération de l'ID numérique depuis le fichier CSV
        description = None
        
        # Recherche de la description associée à l'ID dans le dictionnaire
        for key, value in dico_lum.items():
            if value == description_id:
                description = key
                break

        if description is not None and description not in descriptions:  # Vérification de l'unicité de la description
            mycursor.execute("INSERT IGNORE INTO descr_lum (description) VALUES (%s)", (description,))
            mydb.commit()
            descriptions.add(description)  # Ajout de la description au set pour éviter les doublons


#################### DESCR_ATHMO ####################
# Dictionnaire pour convertir les descriptions en id
dico_athmo = {
    "Brouillard – fumée": 1,
    "Neige – grêle": 2,
    "Pluie forte": 3,
    "Normale": 4,
    "Temps éblouissant": 5,
    "Pluie légère": 6,
    "Temps couvert": 7,
    "Vent fort – tempête": 8,
    "Autre": 9
}

with open('../end-project-A3-Web/scrpits/datas.csv', 'r') as file:
    descriptions = set()  # Utilisation d'un set pour stocker les descriptions uniques
    first_line = True  # Variable pour suivre la première ligne

    for line in file:
        if first_line:
            first_line = False
            continue  # Passe à l'itération suivante pour ignorer la première ligne

        line = line.split(',')
        description_id = int(line[10].strip())  # Récupération de l'ID numérique depuis le fichier CSV
        description = None
        
        # Recherche de la description associée à l'ID dans le dictionnaire
        for key, value in dico_athmo.items():
            if value == description_id:
                description = key
                break

        if description is not None and description not in descriptions:  # Vérification de l'unicité de la description
            mycursor.execute("INSERT IGNORE INTO descr_athmo (description) VALUES (%s)", (description,))
            mydb.commit()
            descriptions.add(description)  # Ajout de la description au set pour éviter les doublons

#################### DESCR_ETAT_SURF ####################
# Dictionnaire pour convertir les descriptions en id
dico_etat_surf = {    
    "Verglacée": 1,
    "Enneigée": 2,
    "Mouillée": 3,
    "Normale": 4,
    "Corps gras – huile": 5,
    "Boue": 6,
    "Flaques": 7,
    "Inondée": 8,
    "Autre": 9,
}

with open('../end-project-A3-Web/scrpits/datas.csv', 'r') as file:
    descriptions = set()  # Utilisation d'un set pour stocker les descriptions uniques
    first_line = True  # Variable pour suivre la première ligne

    for line in file:
        if first_line:
            first_line = False
            continue  # Passe à l'itération suivante pour ignorer la première ligne

        line = line.split(',')
        description_id = int(line[12].strip())  # Récupération de l'ID numérique depuis le fichier CSV
        description = None
        
        # Recherche de la description associée à l'ID dans le dictionnaire
        for key, value in dico_etat_surf.items():
            if value == description_id:
                description = key
                break

        if description is not None and description not in descriptions:  # Vérification de l'unicité de la description
            mycursor.execute("INSERT IGNORE INTO descr_etat_surf (description) VALUES (%s)", (description,))
            mydb.commit()
            descriptions.add(description)  # Ajout de la description au set pour éviter les doublons

#################### DESCR_DISPO_SECU ####################

dico_dispo_secu = {
    "Utilisation d'une ceinture de sécurité ": 1,
    "Utilisation d'un casque ": 2,
    "Utilisation d'un dispositif enfant": 3,
    "Présence de ceinture de sécurité non utilisée ": 4,
    "Présence d'une ceinture de sécurité - Utilisation non déterminable": 5,
    "Présence d'un équipement réfléchissant non utilisé": 6,
    "Présence d'un casque non utilisé ": 7,
    "Autre - Non déterminable": 8,
    "Autre - Non utilisé": 9,
    "Utilisation d'un équipement réfléchissant ": 10,
    "Présence d'un casque - Utilisation non déterminable": 11,
    "Autre - Utilisé": 12,
    "Présence d'un dispositif enfant non utilisé": 13,
    "Présence dispositif enfant - Utilisation non déterminable": 14,
    "Présence équipement réfléchissant - Utilisation non déterminable": 15,
}

with open('../end-project-A3-Web/scrpits/datas.csv', 'r') as file:
    descriptions = set()  # Utilisation d'un set pour stocker les descriptions uniques
    first_line = True  # Variable pour suivre la première ligne

    for line in file:
        if first_line:
            first_line = False
            continue  # Passe à l'itération suivante pour ignorer la première ligne

        line = line.split(',')
        description_id = int(line[17].strip())  # Récupération de l'ID numérique depuis le fichier CSV
        description = None
        
        # Recherche de la description associée à l'ID dans le dictionnaire
        for key, value in dico_dispo_secu.items():
            if value == description_id:
                description = key
                break

        if description is not None and description not in descriptions:  # Vérification de l'unicité de la description
            mycursor.execute("INSERT IGNORE INTO descr_dispo_secu (description) VALUES (%s)", (description,))
            mydb.commit()
            descriptions.add(description)  # Ajout de la description au set pour éviter les doublons

mydb.close()