# App Event Analyzer
This repository provides a boilerplate prototype of an application-level Intrusion Detection System (IDS) framework, inspired by the concepts outlined in OWASP AppSensor.
The goal of this project is to demonstrate a minimal yet functional approach to detecting anomalous user behavior at the application level.

## Overview
The system is designed to be deployed as a standalone microservice that integrates with existing application architectures. Its primary function is to monitor and analyze user activity in real or near-real time.

### Demo
A basic machine learning-powered detection mechanism is included as a proof of concept.
A configured Laravel job receives application events, evaluates them, and invokes a trained AWS SageMaker model to detect anomalies. Upon detection, the system automatically issues a Slack alert as a security response.
