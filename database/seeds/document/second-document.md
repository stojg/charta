# Centralised Logging [beta]

 - Stig Lindqvist <stig@silverstripe.com>
 - Sean Harvey <sean@silverstripe.com>
 - Mateusz Uzdowski <mateusz@silverstripe.com>

## Table of Content
 - [Summary](#summary)
 - [Background](#background)
 - [Goals and SLA](#solution-goals-and-service-level-agreements-sla)
 - [Out of scope](#out-of-scope)
 - [High level design](#high-level-design)
 - [Alternatives considered](#alternatives-considered)
 - [Special constraints](#special-constraints)
 - [Security concerns](#security)
 - [Backups](#backups)

## Purpose

This document provides a high level overview for the SilverStripe Platform centralised log collection system. It will give the reader an understanding of what components are used, and how they connect.

## Summary

Centralised logging server collects logs produced by multiple servers so that they can be easily browsed and searched in one location. It permits to view logs for servers that no longer exist (i.e. destroyed as a result of autoscaling events) and allows fine-grained control over who has access to what.

## Background

One important source of information for Platforms Adminstrators and Customers are log events that contains important information that can help with incident resolution, forecasting and analysis.

In SilverStripe Platform resources can be created or destroyed at any moment due to autoscaling events or deployment of newer version. For example if a web server instance stops responding to health checks it will be destroyed and replaced with a new instancce. If the logs are only saved on the local filesystem, the information why the server became unhealthy is lost.

The elasticity and the quantity of servers would also make it hard to combine the individual log entries for an event that might have occurred across multiple servers due to load balancing and redundancy.

Having log events shipped to external storage can also assist with incident resolution by helping to audit "hacked" servers where the local logs may have been altered after an intrusion.

## Solution goals and Service Level Agreements (SLA)

 - Ops can create new user accounts and restrict their access to the logs for a sub-set of all sites
 - Those users can log into a server, both from within our office and from the offices of SSP dev teams
 - There is a mechanism (e.g. a developer doc for the cloudbusters/ops) for adding in new streams of log data
 - Ops will be able to activate this facility for all existing SSP sites with a well-understood redeploy process
 - The log server has at least 99.5% uptime (including the log-in function working)
 - 99% of all log events are stored
 - The architecture supports at least 50 sites
 - PHP errors, Apache/Nginx errors, and Access logs appear in the logs
 - There is a less than 5 minute delay in the log UI being populated with log data
 - Logs entries are encrypted in flight

## Out of scope

 - A fully clustered and highly available (HA) solution
 - External client access to the logging system.

## High level design

Environments send encrypted log events to logging stack over the public network. The data is transported via logstash-forwarder / logstash utilities.

Graylog stores and indexes these events in Elasticsearch. Further to that, the Graylog web UI provides authentication and authorisation for its frontend interface for log browsing and filtering. Both Graylog and Graylog web UI use MongoDB as a configuration store.

Regarding availability, the design of the logging stack is simplistic: one monolithic server running all necessary services. Accepting this kind of single point of failure in exchange for the simplicity of maintenance is based on the following observations:

 - we prefer to avoid the complexity of maintaining an Elasticsearch cluster at the moment - it may not be needed to achieve the SLA.
 - without such a cluster Elasticsearch becomes a single point of failure. Everything else in the logging stack relies on it, so there is no useful subdivision of services we can do.
 - sending agents can pause sending logs if the logging stack is not available, hence an outage of the logging stack will not cause loss of data, just a loss of access to the browse facility.

Our hope is the logging stack will remain available during 99.5% of the time, even without clustering (including disaster recovery situations). Based on our proof of concept work, we are also guessing this will be able to handle 50 agents out of the box.

Should a need arise to accept more agents, or more search queries, the only way to scale this solution is vertically (i.e. add more resources to the single machine).

If there was a need to increase availability, a "hot standby" could be created in another Availability Zone (AZ). This would require finding a way to "tee" the data before it reaches logstash, but otherwise the logging stack would remain the same.

Note that if the VPN tunnel used by the AD integration is down, the only way to log in to the service is via the master admin password that is setup during provisioning.

### Sending agent

This is part of the system that sends the log events. Any EC2 based on the standard AMIs is a sender, which includes a web or nfs box. The logs are sent over encrypted channel to the logger stack.

__rsyslog__

Rsyslog is the installed on agent machines and receives events from the running system and stores them to local logfiles or remote destinations. Free and open source.

__logstash-forwarder__

logstash-forwarder, by Elasticsearch, is a small binary that "tail" log files and send them encrypted to a logstash server. Free and open source.

The forwarder is installed on the agent machines and forwards logs to the logstash server running on the logging stack. It can handle log rotation and has very little impact on the agent resources.

### Logging stack (receiver)

Logger stack receives the logs, stores and indexes them.

__logstash__

Logstash, by Elasticsearch, is a service for managing events and logs. It's commonly used as a proxy that can receive and send from/to multiple locations. Free and open source.

Logstash decrypts the traffic received from logstash-forwarder on the agents, and can additionally mutate entries before sending them out via several available protocols.

__graylog-server__

This java application receives events, tags and pushes them into Elasticsearch for indexing. It can be managed by a REST API. Free and open source.

__graylog-web__

This java application is deployed individually from the server and provides a user interface to a backend that connects to the server through the REST API. Free and open source.

__elasticsearch__

This java application is built in java on top of Lucene and provides a distributed RESTful search engine built. Free and open source.

__mongodb__

A NoSQL database used as the backend database for graylog, containing users and other graylog-specific data. Free and open source.

### Network communication diagram

[![Network communication diagram](_img/ssp_centralised_logging_network_communication.png)](_img/ssp_centralised_logging_network_communication.png)

The flow of a syslog entry is as follows:

1. On the agent, log files are sent out by logstash-forwarder over the wire via TLS to logstash on the logging stack.
2. Logstash terminates the TLS, transforms the logs and sends them to graylog.
3. Graylog receives the logs via GELF UDP input, and pushes them to an elasticsearch service that indexes the logs.
4. Logs are available to be browsed through the web interface.

## Security

Graylog can receive syslog directly, but at the moment it can't receive encrypted traffic. To ensure that log traffic cannot be intercepted, we are using Logstash as a transport.

Access to both logstash and the Graylog Web UI should be protected by a SSL certificate deployed to the ELB (Elastic Load Balancer).

Anyone having root access to the instance can manipulate the logs and storage of the service. They will also have access to the SilverStripe internal network via the VPN.

## Backups

Snapshots of the elasticsearch and MongoDB data for Graylog are done daily to an S3 bucket.
There are scripts on the logging server itself which perform this backup, and others for restoring the latest snapshots when needed.

## Rainforest implementation

See [creation](creation.md) docs.

## Alternatives considered

### PaperTrail SaaS

Initial test with the Skinny was using papertrails as a log receiver. Their UI and search is excellent and also provides a CLI tools to do the same, however the cost was to high and wouldn't scale with new clients on the platform. They would allow sending syslogs over TLS as well.

### Other Saas providers

Other saas providers did not check any of the required goals, like security, user management, pricing, search interface or ease of setup. Several enterprise solutions was quickly looked into (splunk etc).

### The ELK Stack - Elasticsearch, logstash and Kibana

The first POC of a centralised log server that worked reasonable well. However it didn't support any user logins and was hard to maintain elasticsearch by itself due to no build in management tools.

## Resources

- [Graylog architecture](http://docs.graylog.org/en/1.0/pages/architecture.html)
