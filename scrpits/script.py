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

# Creation du curseurc
mycursor = mydb.cursor()

#récupère les commandes de model.sql et les exécute
with open('model.sql', 'r') as file:
    commands = file.read().split(';')
    for command in commands:
        mycursor.execute(command)
        mydb.commit()
mydb.close()