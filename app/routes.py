from app import app
from flask import Flask, render_template, redirect, url_for

@app.route('/')
def login():
  return render_template("login.html")

@app.route('/home')
def home():
  usuario = "Saci de Patinete"
  return render_template("home.html", usuario=usuario)