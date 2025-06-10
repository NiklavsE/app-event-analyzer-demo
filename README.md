# App Event Analyzer
This repository provides a boilerplate prototype of an application-level Intrusion Detection System (IDS) framework, inspired by the concepts outlined in OWASP AppSensor.
The goal of this project is to demonstrate a minimal yet functional approach to detecting anomalous user behavior at the application level.

## Overview
The system is designed to be deployed as a standalone microservice that integrates with existing application architectures. Its primary function is to monitor and analyze user activity in real or near-real time.
It follows a three-stage processing pipeline:
- event consumption
- event evaluation
- security response

### Demo
A basic machine learning-powered detection mechanism is included as a proof of concept that is able to detect abuse of configured actions.
- Application events are captured and stored in the database (or from other queue providers as configured).
- Events are matched against predefined rules.
- System aggregates event frequency within a 10-minute window.
- If the frequency threshold is met, the event context is analyzed by AWS SageMaker's anomaly detection model
   - Analysis considers factors like:
     - Time of day and day of week
     - Business hours
     - Historical user activity patterns
     - Event frequency
- If the anomaly score exceeds the configured threshold, a security response is triggered
   - Notifications are sent via the configured Slack channel
   - System implements a 10-minute cooldown period to prevent alert spam
