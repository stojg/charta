# Metrics collection

 - Stig Lindqvist <stig@silverstripe.com>
 - Sean Harvey <sean@silverstripe.com>
 - Mateusz Uzdowski <mateusz@silverstripe.com>

## Table of Content
 - [Purpose](#purpose)
 - [Summary](#summary)
 - [Background](#background)
 - [Goals and SLA](#solution-goals-and-service-level-agreements-sla)
 - [Out of scope](#out-of-scope)
 - [High level design](#high-level-design)
 - [Alternatives considered](#alternatives-considered)
 - [Special constraints](#special-constraints)
 - [Security concerns](#security-concerns)
 - [Backups](#backups)
 - [Rainforest implementation](#rainforest-implementation)
 - [Alternatives considered](#alternatives-considered)
 - [Resources](#resources)
 
## Purpose

This document aims to provide a high level overview for the SilverStripe Platform metric collection & analysis system. It will give the reader an understanding of what components are used, and how they connect.

## Summary

Gathering system and application metrics is important for debugging, incident resolution and capacity planning. It can also potentially be used to detect anomalies for resolving issues before they turn catastrophic.
 
## Background

The current metrics on the SilverStripe platform is collected every minute by the AWS Cloudwatch. However Cloudwatch can only report on  information that AWS can "see". It cannot report memory usage, disk usage or process information.

## Solution goals and Service Level Agreements (SLA)

 - Metric collection is encrypted (e.g. SSH tunnel, VPN, or SSL-encrypted protocol)
 - There is one metric system (may be multi-az/scaling) for all clusters, cross-cluster communication needs to be addressed
 - Scaling is considered: the system can scale to 100 sites without re-architecting
 - Metric server is multi-AZ, so that an AZ-loss won't prevent logging in the other AZ, but metric collection doesn't necessarily need to be AZ-redundant.
 - Metrics data is backed up to S3 or Glacier
 - Metrics are viewable within five minutes
 - Metric retention is as follows:
   - keep 4 hours of data with 1 minute granularity 
   - keep 3 days of data with 5 minutes granularity 
   - keep 30 days of data with 1 hour granularity 
   - keep one year of data with 4 hours granularity 
 - The system be able to deliver and process 50 000 unique metrics per minute

## Out of scope

 - Purchasing this as a service
 - Anomaly detection

## High level design

Metrics are regularly generated on all virtual machines and sent to metric-collecting backends through Amazon Kinesis (streaming service). Backends run worker deamons which continuously read data off this stream. Backends do not interact with each other.

For the SilverStripe Platform administrators there is a web interface that can be used for finding and analysing metrics, which gives full access to all metrics, and all analysis tools.

Deploynaut surfaces a subset of this information to Platform users via Deploynaut, provided they are allowed to view them.

The metrics are also backed up from the metric backends into S3 buckets with a lifecycle for Glacier archival over a longer term. 

There are two redundant backends storing the data, placed in two different Availability Zones (AZs). An outage in a single AZ will not prevent the system from collecting metrics, although the offline workers will miss some data. This data will never be back-filled, however this is alleviated by the graphite-web's ability to aggregate the data over multiple backends. Dashboard users/REST consumers will get the full picture as long as the data is present in at least one of the backends.

The web interface may also separately be duplicated across multiple AZs to make it more available.

Here is a review of all components of the metric collection system, starting from the metric collection, all the way to viewing the metrics by the user.

__Gathering metrics__

Each instance has a cronjob that gathers metrics once per minute and sends them to graphite. Metrics are gathered similarly to CWP, with a set of PHP, Python and bash scripts generating and sending data in graphite's plain text format.

These are sent to the local *kinesis-push* daemon on port 2003. Here is an example of how such a metric could be send through bash:

```
printf "server.us-west-1.ip-172-31-19-230.memory.swap-total 0 1429736596\n" | nc localhost 2003
```

__Streaming data (Kinesis)__

Kinesis is a streaming transport for data. It's based on a rolling 24-hour window over the data - i.e. any data written is accessible anytime within the next 24-hours. Reading can occur at an arbitrary location (including the beginning or the end of the window). The data is not searchable, but can be easily read sequentially with the use of iterators once a starting point is established.

|Non-funcitonal requirement|Kinesis|
|---|---|
| latency < 5 min | Data latency "[is typically less than 1 second](http://docs.aws.amazon.com/kinesis/latest/dev/introduction.html)" |
| guaranteed delivery | Data records are accessible for 24 hours from the time they are added to a stream |
| msg size 20kB | 50kB per data blob |
| handling of multiple workers (mutex issues) | No contention - use of individual iterators to "follow" the stream. Can add new workers any time at any point of the stream. |
| throughput 333 kB/sec | One shard of 2MB/sec should be enough, assuming two workers |
| throughput 17 records/sec, 20kB each | One shard should be enough: 5 read transactions per second, 10 MB per read transaction, assuming two workers |
| transport is encrypted and authenticated | IAM |
| price | One shard should suffice - US$12.17 |
| cross region and outside of aws network | Cross-account IAM access |

On the sending side *kinesis-push* daemon accepts metrics on localhost's TCP connection, which makes it transparent to metric producers. It aggregates the data for certain period of time, and then sends it out as one Kinesis record.

__Receiving metrics__

On the receiving side *kinesis-worker* daemon reads the stream independently of other *kinesis-push* and *kinesis-worker* daemons. It reads the stream at specified intervals, and passes the received metrics back onto a TCP connection, effectively delivering it to graphite (i.e. carbon-relay daemon).

Upon initialisation, the worker will always start reading at the end of the 24-hour window - it will not attempt to back-fill missed data. This is the simplest approach: we don't need to store worker's latest sequence location to recover from the crash, nor we need to overprovision Kinesis to allow failed workers to catch-up. 

The disadvantage is that some workers might miss some of the data. Fortunately the overarching graphite-web can aggregate metrics from multiple backends and fill in the blanks.

__Graphite__

Graphite is a scalable real-time graphing system we are already familiar with, as we use it internally and on CWP.

Graphite comes in several components. We use carbon-relay, carbon-cache, and graphite-web. One additional component that we don't use is carbon-aggregator.

__Graphite: carbon-relay__

carbon-relay is a router for metrics. It can duplicate metric streams and also perform metric sharding based on a hash algorithm. Here, carbon-relay is used only to forward metrics to the carbon-cache.

__Graphite: carbon-cache__

carbon-cache accepts metrics over various protocols and writes them to disk as efficiently as possible. This requires caching metric values in RAM as they are received, and flushing them to disk on an interval using the underlying whisper library.

It also makes those metrics available to the frontend, to cover for the fact that some data might not have been written to the disk yet (it can be served straight from RAM).

__Graphite: graphite-web__

graphite-web is a metric frontend written in Django (python). It comes with a UI that allows the user to process data series using many inbuilt functions (such as averaging or computing derivatives) and produce graphs.

It is designed to read data directly from carbon-cache, or indirectly from other instance(s) of graphite-web. It can also perform deduplication if it's reading the data from multiple backend replicas.

graphite-web needs a webserver to run, but can interface through WSGI, meaning both Apache and Nginx can be used.

graphite-web has a mature API, which can be leveraged to display the data to clients, or create dashboards.

### Network communication diagram 

![network communication](./img/network_communication.png)

## Security

Access to the graphite-web is controlled by an IP whitelist, containing Wellington and Auckland offices.

Communication between the graphite-web and the backends is over plain-text HTTP, but is confined to the private cluster network. As a result, the metric stack requires a dedicated cluster.

Metric payload is transmitted over Kinesis. No passwords or tokens are stored on either side. On the push side, HTTPS and temporary AWS credentials are used (using the ARN role provided by the metrics stack). Each new account requires adding an additional principal to that ARN role.

On the pull side, ambient AWS credentials are used: EC2 is permanently given permission to access the Kinesis stream.

## Backups

Metrics are backed up to S3 via cronjobs and AWS CLI.

## Rainforest implementation

The solution has been mapped into several new layouts (*kinesis*, *metrics* and *metricsweb*). Two of these layouts contain EC2s and depend on two new templates to be used (*graphite* and *graphite-web*).

![Rainforest components](./img/ssp_metric_solution_rainforest_components.png)

## Alternatives considered

__influxdb__ as a replacement to Graphite. Discarded as an unfamiliar solution.

__prometheus__ as a replacement to Graphite. Discarded as an unfamiliar solution.

__nagios__ as a replacement to Graphite. Discarded as an ugly and unmaintainable solution.

__heka__ as a replacement to the command line pusher/reader. Discarded as an overkill - it would require an implementation of Kinesis plugin anyway, so it seemed simpler to implement our own generic transport tool.

__SQS__ as a replacement for Kinesis. Discarded as not appropriate for this application - we prefer to write once and read many times. SQS is more expensive, and we wouldn't be able to back-fill messages if we chose so in the future (which we can do with Kinesis, which stores all data read-only for up to 24 hours).

## Resources

- [Graphite Overview](http://graphite.readthedocs.org/en/latest/overview.html)
- [The architecture of clustering graphite](https://grey-boundary.io/the-architecture-of-clustering-graphite/)
- [Metrics environment creation](creation.md)
- [Rainforest documentation](https://sites.google.com/a/silverstripe.com/aws/aws-mp/rainforest-cloudformation-tools#TOC-kinesis:create)
