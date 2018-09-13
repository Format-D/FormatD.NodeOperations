
# FormatD.NodeOperations

CLI commands for moving and copying nodes.
This is useful if you want to move nodes with a lot of childnodes. This can take a long while and make the UI crash.

## Move nodes

Example:
```
    ./flow nodeoperations:move 1fd7d006-d1db-4s5c-ac89-7170fe22ce24 a77eaf64-eed9-4deb-9d21-52325d04d761 into
```
## Copy nodes

Example:
```
    ./flow nodeoperations:copy --name="New NodeName" 1fd7d006-d1db-4s5c-ac89-7170fe22ce24 a77eaf64-eed9-4deb-9d21-52325d04d761 after
```